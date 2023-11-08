const orderingFormList = (form) => {
  const ranks = form.querySelectorAll('td.draggable input[data-rank]');
  ranks.forEach((rank, index) => {
    rank.value = index.toString();
  });
};

const bindSortListForm = (form) => {
  form.addEventListener('submit', () => {
    orderingFormList(form);
  });
};

[...document.querySelectorAll('[data-sort-list]')].forEach((element) => {
  bindSortListForm(element);
});
