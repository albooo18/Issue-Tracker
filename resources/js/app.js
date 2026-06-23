const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

const request = async (url, { method = 'POST', body } = {}) => {
	const response = await fetch(url, {
		method,
		headers: {
			Accept: 'application/json',
			'X-CSRF-TOKEN': csrfToken,
		},
		body,
	});

	const data = await response.json().catch(() => ({}));

	if (!response.ok) {
		const error = new Error(data.message || 'Request failed.');
		error.payload = data;
		throw error;
	}

	return data;
};

const getValidationMessage = (payload) => {
	if (!payload?.errors) {
		return payload?.message || 'Something went wrong.';
	}

	return Object.values(payload.errors).flat().join(' ');
};

const initIssuePage = (root) => {
	const commentsUrl = root.dataset.commentsUrl;
	const commentsList = root.querySelector('[data-comments-list]');
	const commentsPagination = root.querySelector('[data-comments-pagination]');
	const commentsForm = root.querySelector('[data-comment-form]');
	const commentsStatus = root.querySelector('[data-comments-status]');
	const tagsWrapper = root.querySelector('[data-tags-wrapper]');
	const tagsForm = root.querySelector('[data-tag-form]');
	const tagsStatus = root.querySelector('[data-tags-status]');
	const membersWrapper = root.querySelector('[data-members-wrapper]');
	const membersForm = root.querySelector('[data-member-form]');
	const membersStatus = root.querySelector('[data-members-status]');

	const setStatus = (element, message, kind = 'info') => {
		if (!element) {
			return;
		}

		element.textContent = message || '';
		element.dataset.state = kind;
	};

	const renderPagination = (meta) => {
		if (!commentsPagination) {
			return;
		}

		const pieces = [];

		if (meta.prev_page_url) {
			pieces.push(`<button type="button" class="btn btn-secondary" data-page="${meta.current_page - 1}">Previous</button>`);
		}

		pieces.push(`<span class="subtle">Page ${meta.current_page} of ${meta.last_page}</span>`);

		if (meta.next_page_url) {
			pieces.push(`<button type="button" class="btn btn-secondary" data-page="${meta.current_page + 1}">Next</button>`);
		}

		commentsPagination.innerHTML = pieces.join(' ');
	};

	const loadComments = async (page = 1) => {
		try {
			const url = new URL(commentsUrl, window.location.origin);
			url.searchParams.set('page', String(page));

			const data = await request(url.toString(), { method: 'GET' });

			commentsList.innerHTML = data.html;
			renderPagination(data.pagination);
			setStatus(commentsStatus, data.pagination.total ? '' : 'No comments loaded yet.', 'info');
		} catch (error) {
			setStatus(commentsStatus, error.message, 'error');
		}
	};

	const replaceTags = (tagsHtml) => {
		if (tagsWrapper) {
			tagsWrapper.innerHTML = tagsHtml;
		}
	};

	const replaceMembers = (membersHtml) => {
		if (membersWrapper) {
			membersWrapper.innerHTML = membersHtml;
		}
	};

	loadComments(1);

	commentsPagination?.addEventListener('click', (event) => {
		const button = event.target.closest('[data-page]');

		if (!button) {
			return;
		}

		loadComments(Number(button.dataset.page));
	});

	commentsForm?.addEventListener('submit', async (event) => {
		event.preventDefault();
		setStatus(commentsStatus, 'Posting comment...', 'info');

		try {
			const formData = new FormData(commentsForm);
			const data = await request(commentsForm.dataset.commentUrl, { body: formData });

			commentsList.querySelector('.empty-inline')?.remove();
			commentsList.insertAdjacentHTML('afterbegin', data.comment_html);
			commentsForm.reset();
			setStatus(commentsStatus, 'Comment added.', 'success');
		} catch (error) {
			setStatus(commentsStatus, getValidationMessage(error.payload), 'error');
		}
	});

	tagsForm?.addEventListener('submit', async (event) => {
		event.preventDefault();
		setStatus(tagsStatus, 'Attaching tag...', 'info');

		try {
			const formData = new FormData(tagsForm);
			const data = await request(tagsForm.dataset.tagUrl, { body: formData });

			replaceTags(data.tags_html);
			tagsForm.reset();
			setStatus(tagsStatus, 'Tag attached.', 'success');
		} catch (error) {
			setStatus(tagsStatus, getValidationMessage(error.payload), 'error');
		}
	});

	tagsWrapper?.addEventListener('click', async (event) => {
		const button = event.target.closest('[data-detach-tag]');

		if (!button) {
			return;
		}

		try {
			const data = await request(button.dataset.detachUrl, { method: 'DELETE' });

			replaceTags(data.tags_html);
			setStatus(tagsStatus, 'Tag removed.', 'success');
		} catch (error) {
			setStatus(tagsStatus, getValidationMessage(error.payload), 'error');
		}
	});

	membersForm?.addEventListener('submit', async (event) => {
		event.preventDefault();
		setStatus(membersStatus, 'Assigning member...', 'info');

		try {
			const formData = new FormData(membersForm);
			const data = await request(membersForm.dataset.memberUrl, { body: formData });

			replaceMembers(data.members_html);
			membersForm.reset();
			setStatus(membersStatus, 'Member assigned.', 'success');
		} catch (error) {
			setStatus(membersStatus, getValidationMessage(error.payload), 'error');
		}
	});

	membersWrapper?.addEventListener('click', async (event) => {
		const button = event.target.closest('[data-detach-member]');

		if (!button) {
			return;
		}

		try {
			const data = await request(button.dataset.detachUrl, { method: 'DELETE' });

			replaceMembers(data.members_html);
			setStatus(membersStatus, 'Member removed.', 'success');
		} catch (error) {
			setStatus(membersStatus, getValidationMessage(error.payload), 'error');
		}
	});
};

const initIssueSearch = (root) => {
	const form = root.querySelector('[data-search-form]');
	const results = root.querySelector('[data-issues-results]');
	const pagination = root.querySelector('[data-issues-pagination]');
	const searchUrl = root.dataset.searchUrl;

	if (!form || !results || !pagination || !searchUrl) {
		return;
	}

	const debounce = (callback, delay = 300) => {
		let timer;

		return (...args) => {
			clearTimeout(timer);
			timer = setTimeout(() => callback(...args), delay);
		};
	};

	const renderPagination = (meta) => {
		const pieces = [];

		if (meta.prev_page_url) {
			pieces.push(`<button type="button" class="btn btn-secondary" data-issues-page-number="${meta.current_page - 1}">Previous</button>`);
		}

		pieces.push(`<span class="subtle">Page ${meta.current_page} of ${meta.last_page}</span>`);

		if (meta.next_page_url) {
			pieces.push(`<button type="button" class="btn btn-secondary" data-issues-page-number="${meta.current_page + 1}">Next</button>`);
		}

		pagination.innerHTML = pieces.join(' ');
	};

	const search = async (page = 1) => {
		const formData = new FormData(form);
		const url = new URL(searchUrl, window.location.origin);

		formData.forEach((value, key) => {
			if (value) {
				url.searchParams.set(key, value.toString());
			}
		});

		url.searchParams.set('page', String(page));

		const data = await request(url.toString(), { method: 'GET' });
		results.innerHTML = data.cards_html;
		renderPagination(data.pagination);
	};

	const debouncedSearch = debounce(() => {
		search(1).catch(() => {
			results.innerHTML = '<div class="panel empty-state"><h2 class="panel-title">Search failed</h2><p class="subtle">Please try again.</p></div>';
		});
	}, 250);

	form.addEventListener('submit', (event) => {
		event.preventDefault();
		search(1).catch(() => {
			results.innerHTML = '<div class="panel empty-state"><h2 class="panel-title">Search failed</h2><p class="subtle">Please try again.</p></div>';
		});
	});

	form.addEventListener('input', debouncedSearch);
	form.addEventListener('change', () => {
		search(1).catch(() => {
			results.innerHTML = '<div class="panel empty-state"><h2 class="panel-title">Search failed</h2><p class="subtle">Please try again.</p></div>';
		});
	});

	pagination.addEventListener('click', (event) => {
		const button = event.target.closest('[data-issues-page-number]');

		if (!button) {
			return;
		}

		search(Number(button.dataset.issuesPageNumber)).catch(() => {
			results.innerHTML = '<div class="panel empty-state"><h2 class="panel-title">Search failed</h2><p class="subtle">Please try again.</p></div>';
		});
	});
};

document.addEventListener('DOMContentLoaded', () => {
	const issueRoot = document.querySelector('[data-issue-page]');
	const issueSearchRoot = document.querySelector('[data-issues-page]');

	if (issueRoot) {
		initIssuePage(issueRoot);
	}

	if (issueSearchRoot) {
		initIssueSearch(issueSearchRoot);
	}
});
