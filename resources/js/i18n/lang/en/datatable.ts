export default {
  buttons: {
    filter: 'Filters',
    unFilter: 'Clear filters',
    visibility: 'View',
    sortAsc: 'Ascending',
    sortDesc: 'Descending',
    hideColumn: 'Hide',
  },
  state: {
    empty: 'No data available in table',
  },
  pagination: {
    first: 'Go to first page',
    last: 'Go to last page',
    previous: 'Go to previous page',
    next: 'Go to next page',
    pageInfo: 'Page {{current}} of {{total}}',
    totalRows_one: '{{count}} row',
    totalRows_many: '{{count}} rows',
    totalRows_other: '{{count}} rows',
    totalRowsSelected_one: '{{selected}} of {{count}} row selected',
    totalRowsSelected_many: '{{selected}} of {{count}} rows selected',
    totalRowsSelected_other: '{{selected}} of {{count}} rows selected',
    rowsPerPage: 'Rows per page',
  },
  confirmation: {
    title: 'Are you sure?',
    description: 'This action cannot be undone.',
    action: 'Confirm',
  },
  actions: {
    delete: 'Delete',
    edit: 'Edit',
    view: 'View',
  },
  selection: {
    title: 'Select',
    selectAll: 'All pages',
    selectCurrent: 'Current page',
  },
  boolean: {
    true: 'Yes',
    false: 'No',
  },
} as const;
