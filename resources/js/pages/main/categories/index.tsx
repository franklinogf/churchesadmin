import { Datatable } from '@/components/datatable/datatable';
import { CategoryForm } from '@/components/forms/category-form';
import { PageTitle } from '@/components/PageTitle';
import { Button } from '@/components/ui/button';
import { TenantPermission } from '@/enums/TenantPermission';
import { useUser } from '@/hooks/use-user';
import AppLayout from '@/layouts/app-layout';
import { type Tag } from '@/types/models/tag';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { columns } from './includes/columns';

interface IndexPageProps {
  categories: Tag[];
}
export default function Index({ categories }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();
  const [open, setOpen] = useState(false);
  return (
    <AppLayout breadcrumbs={[{ title: tPages(($) => $.main.categories.index.categories) }]} title={tPages(($) => $.main.categories.index.categories)}>
      <PageTitle>{tPages(($) => $.main.categories.index.categories)}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <CategoryForm open={open} setOpen={setOpen} />
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.CATEGORIES_CREATE) && (
              <Button size="sm" onClick={() => setOpen(true)}>
                {tPages(($) => $.main.categories.index.addModel, { model: tPages(($) => $.main.categories.index.category) })}
              </Button>
            )
          }
          columns={columns}
          data={categories}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
