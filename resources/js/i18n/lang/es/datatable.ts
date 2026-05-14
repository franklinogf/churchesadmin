export default {
  buttons: {
    filter: 'Filtros',
    visibility: 'Ver',
    unFilter: 'Quitar filtros',
    sortAsc: 'Ordenar ascendente',
    sortDesc: 'Ordenar descendente',
    hideColumn: 'Ocultar columna',
  },
  state: {
    empty: 'No hay datos disponibles',
  },
  pagination: {
    first: 'Primero',
    last: 'Último',
    previous: 'Anterior',
    next: 'Siguiente',
    pageInfo: 'Página {{current}} de {{total}}',
    rowsPerPage: 'Filas por página',
    totalRows_one: '{{count}} fila',
    totalRows_many: '{{count}} filas',
    totalRows_other: '{{count}} filas',
    totalRowsSelected_one: '{{selected}} de {{count}} fila seleccionada',
    totalRowsSelected_many: '{{selected}} de {{count}} filas seleccionadas',
    totalRowsSelected_other: '{{selected}} de {{count}} filas seleccionadas',
  },
  confirmation: {
    title: 'Confirmar acción',
    description: '¿Estás seguro de que deseas realizar esta acción?',
    action: 'Confirmar',
  },
  actions: {
    delete: 'Eliminar',
    edit: 'Editar',
    view: 'Ver',
  },
  selection: {
    title: 'Selección',
    selectAll: 'Seleccionar todo',
    selectCurrent: 'Seleccionar actual',
  },
  boolean: {
    true: 'Sí',
    false: 'No',
  },
} as const;
