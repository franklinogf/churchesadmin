import { Link, router } from '@inertiajs/react';
import type { LucideIcon } from 'lucide-react';
import { Edit2Icon, MoreHorizontalIcon, Trash2Icon, ViewIcon } from 'lucide-react';
import type { ComponentProps } from 'react';

import { DatatableCell } from '@/components/datatable/datatable-cell';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import useConfirmationStore from '@/stores/confirmation-store';
import type { RouteDefinition } from '@/wayfinder';
import { useTranslation } from 'react-i18next';

type ActionMethod = 'get' | 'patch' | 'delete' | 'put' | 'post';

type ActionRoute<T extends ActionMethod> = {
  route: RouteDefinition<T>;
  options?: T extends 'get' ? Omit<ComponentProps<typeof Link>, 'href' | 'children'> : Parameters<typeof router.visit>[1];
};
export interface DatatableCellProps {
  edit?: ActionRoute<'get'>;
  view?: ActionRoute<'get'>;
  delete?: ActionRoute<'delete'>;
  others?: ({
    variant?: ComponentProps<typeof DropdownMenuItem>['variant'];
    label: string;
    icon?: LucideIcon;
  } & ActionRoute<ActionMethod>)[];
  children?: React.ReactNode;
}
function routeIsGet(route: RouteDefinition<ActionMethod>): route is RouteDefinition<'get'> {
  return route.method === 'get';
}
export function DatatableCellActions({ edit, view, delete: del, others, children }: DatatableCellProps) {
  const { t } = useTranslation('datatable');
  const openConfirmation = useConfirmationStore((state) => state.openConfirmation);

  function deleteConfirmation({ route, options }: ActionRoute<'delete'>) {
    openConfirmation({
      title: t(($) => $.confirmation.title),
      description: t(($) => $.confirmation.description),
      onAction: () => {
        router.visit(route, options);
        router.flushAll();
      },
      actionLabel: t(($) => $.confirmation.action),
    });
  }

  function handleAction({ route, options }: ActionRoute<'patch' | 'delete' | 'put' | 'post'>) {
    if (route.method === 'delete') {
      deleteConfirmation({ route, options } as ActionRoute<'delete'>);

      return;
    }

    router.visit(route, options as ActionRoute<'patch' | 'put' | 'post'>['options']);
  }

  return (
    <DatatableCell align="end">
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" size="sm">
            <MoreHorizontalIcon />
            <span className="sr-only">Actions</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          {view && <DatatableCellActionViewItem route={view.route} options={view.options} />}
          {edit && <DatatableCellActionEditItem route={edit.route} options={edit.options} />}
          {children}
          {others?.map(({ label, route, options, icon: Icon, variant }) => {
            return (
              <DatatableCellActionItem
                label={label}
                className="w-full"
                key={label}
                icon={Icon}
                route={routeIsGet(route) ? route : undefined}
                asLink={routeIsGet(route)}
                onSelect={routeIsGet(route) ? undefined : () => handleAction({ route, options } as ActionRoute<'patch' | 'delete' | 'put' | 'post'>)}
                variant={route.method === 'delete' ? 'destructive' : variant}
              />
            );
          })}
          {del && <DatatableCellActionDeleteItem route={del.route} options={del.options} />}
        </DropdownMenuContent>
      </DropdownMenu>
    </DatatableCell>
  );
}

interface DatatableCellActionItemProps extends Omit<ComponentProps<typeof DropdownMenuItem>, 'children' | 'asChild'> {
  label: string;
  icon?: LucideIcon;
  asLink?: boolean;
  route?: RouteDefinition<'get'>;
  options?: ComponentProps<typeof Link> | Parameters<typeof router.visit>[1];
}
export function DatatableCellActionItem({ label, icon: Icon, asLink, route, options, ...props }: DatatableCellActionItemProps) {
  return (
    <DropdownMenuItem className="w-full" {...props} asChild={asLink}>
      {asLink ? (
        <Link href={route} {...options}>
          {Icon && <Icon className="size-4" />}
          {label}
        </Link>
      ) : (
        <>
          {Icon && <Icon className="size-4" />}
          {label}
        </>
      )}
    </DropdownMenuItem>
  );
}

export function DatatableCellActionEditItem({
  route,
  options,
  ...props
}: Partial<ActionRoute<'get'>> & Omit<ComponentProps<typeof DropdownMenuItem>, 'children' | 'asChild'>) {
  const { t } = useTranslation('datatable');

  return <DatatableCellActionItem asLink={!!route} label={t(($) => $.actions.edit)} icon={Edit2Icon} route={route} options={options} {...props} />;
}

export function DatatableCellActionViewItem({
  route,
  options,
  ...props
}: Partial<ActionRoute<'get'>> & Omit<ComponentProps<typeof DropdownMenuItem>, 'children' | 'asChild'>) {
  const { t } = useTranslation('datatable');

  return <DatatableCellActionItem asLink={!!route} label={t(($) => $.actions.view)} icon={ViewIcon} route={route} options={options} {...props} />;
}

export function DatatableCellActionDeleteItem({
  route,
  options,
  ...props
}: Partial<ActionRoute<'delete'>> & Omit<ComponentProps<typeof DropdownMenuItem>, 'children' | 'asChild'>) {
  const { t } = useTranslation('datatable');
  const openConfirmation = useConfirmationStore((state) => state.openConfirmation);

  function deleteConfirmation() {
    openConfirmation({
      title: t(($) => $.confirmation.title),
      description: t(($) => $.confirmation.description),
      onAction: () => {
        if (!route) {
          return;
        }

        router.visit(route, options);
        router.flushAll();
      },
      actionLabel: t(($) => $.confirmation.action),
    });
  }

  return (
    <DatatableCellActionItem label={t(($) => $.actions.delete)} icon={Trash2Icon} variant="destructive" onSelect={deleteConfirmation} {...props} />
  );
}
