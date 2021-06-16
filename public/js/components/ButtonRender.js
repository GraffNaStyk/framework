$.on('click', '.render', (e) => {
    e.preventDefault();
    e.stopPropagation();
    Render(e);
});