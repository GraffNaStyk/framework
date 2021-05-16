$.on('click', '.action', (e) => { $.loaderStart(); let data = $.buttons[e.target.dataset.url]['options']; data['_csrf'] = e.target.dataset.csrf; $.post({ url: e.target.dataset.url, data: data }).then(res => res.json()) .then(res => { if (res.csrf) { $.elements(`button[data-url="${e.target.dataset.url}"]`).forEach((k,v) => { k.dataset.csrf = res.csrf; }); } $.loaderStop(); if (e.target.dataset.refresh) { let component = $.el(`[data-component="${e.target.dataset.refresh}"]`); e.target.dataset.url = component.dataset.fetch; Render(e); } else { callback(res); } })
}); const Render = (e) => { $.loaderStart(); $.render({ url: e.target.dataset.url, el: e.target.dataset.el }).then(res => { $.loaderStop(); res.text().then(result => { if (e.target.dataset.el !== 'modal') { if (e.target.dataset.append) { $.append($.el(`[data-component="${e.target.dataset.el}"]`), result); } else { $.html($.el(`[data-component="${e.target.dataset.el}"]`), result); } } else { showModal($.el('#modal'), result); } }); $.reloadEvents(); RefreshSelects(); });
};
 ; $.on('click', '.render', (e) => { e.preventDefault(); e.stopPropagation(); Render(e);
});
 ; 
const callback = (res) => { if (res.ok) { if (res.params.to !== undefined) { setTimeout(() => { document.location.href = $.url + res.params.to; }, 700); } else if (res.params.reload === true) { setTimeout(() => { document.location.reload(); }, 700); } }
};
 ; $.elements('[data-fetch]').forEach(e => { Render({target: {dataset: e.dataset}}); });  ; let lastRandom = ''; const message = (res, selector) => { let min = Math.ceil(500); let max = Math.floor(150000); let rand = Math.floor(Math.random() * (max - min + 1)) + min; if (lastRandom !== '') { let alert = $.el(`[data-${lastRandom}=""]`); if (alert) { alert.remove(); } } let form = $.el(`form[data-action="${selector}"] .modal-body`); if (form === null) { form = $.el(`form[data-action="${selector}"]`); } lastRandom = rand; $.adjacent(form, ` <div data-${rand}="" class="alert alert-${res.ok ? 'success' : 'danger'}" role="alert"> ${res.msg} </div> `, 'afterbegin');
}
 ; const showModal = (modal, result) => { modal.classList.add('d-block'); modal.setAttribute('style', 'background: rgba(0,0,0,0.7)'); const content = $.el('.modal-content'); content.innerHTML = ''; $.append(content, result);
}; const registerClose = () => { $.on('click', 'button[data-dismiss="modal"]', () => { $.el('#modal').classList.remove('d-block'); $.clear($.el('.modal-content')); $.el('#modal').setAttribute('style', ''); });
}; registerClose();
 ; 
const RefreshSelects = () => { const selectors = $.elements('[data-select="slim"]'); if (selectors) { selectors.forEach((value => { if (value.dataset.url !== undefined && value.dataset.ssid === undefined) { new SlimSelect({ select: value, allowDeselect: true, deselectLabel: '<span class="red">✖</span>', searchingText: 'Wyszukaj...', ajax: (search, callback) => { if (search.length < 3) { callback('Need 3 characters'); return; } $.post(`${value.dataset.url}/${search}`).then(res => { callback(res); }); } }); } else if (value.dataset.ssid === undefined) { new SlimSelect({ select: value, allowDeselect: true, deselectLabel: '<span class="red">✖</span>', searchingText: 'Wyszukaj...', }); } })) }
}; RefreshSelects();
 ; $.on('click', '.confirm', (e) => { let elem = e.target.dataset; const tpl = ` <form data-action="${elem.url}" data-isconfirm=""> <div class="modal-header"> <h5 class="modal-title">${elem.title}</h5> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> </div> <div class="modal-body"> <p>${elem.body}</p> </div> <input type="hidden" name="_csrf" value="${elem.csrf ?? ''}"> <div class="modal-footer"> <button class="btn submit__button">Submit</button> </div> </form> `; $.el('#modal').classList.add('d-block'); $.el('#modal').setAttribute('style', 'background: rgba(0,0,0,0.7)'); $.append($.el('.modal-content'), tpl); $.reloadEvents(); registerClose();
});
 ; $.on('submit', 'form', (e) => { let that = e.target; if (that.dataset.action) { e.preventDefault(); e.stopImmediatePropagation(); $.loaderStart(); $.post({ url: that.dataset.action, form: that, isconfirm: that.dataset.isconfirm }).then(res => { $.loaderStop(); const contentType = res.headers.get('content-type'); if (contentType && contentType.indexOf('application/json') !== -1) { res.json().then(res => { if (res === null || res === '' || res.length === 0) { return false; } if (res.params.modal !== undefined) { Render({ target: { dataset: { url: res.params.modal, el: 'modal' } } }); return; } else if (res.params.html) { $.html($.el(`[data-component="${that.el}"]`), res.html); } else { let modalSelector = $.el('#modal'); if (res.ok && modalSelector.classList.contains('d-block')) { setTimeout(() => { $.el('button[data-dismiss="modal"]').click() }, 500); } } throwFormErrors(res, that.dataset.action); if (that.dataset.reload === undefined) { callback(res); } }); } else { res.text().then(res => { if (res === null || res === '') { return false; } $.html($.el(`[data-component="${that.el}"]`), res); }); } $.reloadEvents(); RefreshSelects(); }); }
});
 ; const throwFormErrors = (res, action) => { $.elements('span.err') .forEach(e => e.remove()); $.elements('.input_error') .forEach(e => e.classList.remove('input_error')); if (res.csrf !== undefined && res.csrf !== false) { $.el(`form[data-action="${action}"] input[name="_csrf"]`).value = res.csrf; } if (Array.isArray(res.inputs)) { res.inputs.forEach((error => { let selector = $.el(`form[data-action="${action}"] input[name="${error.field}"]`); if (selector === null) { selector = $.el(`form[data-action="${action}"] textarea[name="${error.field}"]`); } if (selector === null) { selector = $.el(`form[data-action="${action}"] select[name="${error.field}"]`); } selector.classList.add('input_error'); if (selector.parentElement.children[0].classList.contains('err') === false) { $.adjacent(selector.parentElement, ` <span class="err" data-field="${error.field}"> ${error.msg} </span> `); } })) } if (res.msg) { message(res, action); }
};
 ; 