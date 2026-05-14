import { Datatable } from '@/components/datatable/datatable';
import { SkillForm } from '@/components/forms/skill-form';
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
  skills: Tag[];
}
export default function Index({ skills }: IndexPageProps) {
  const { t: tPages } = useTranslation('pages');
  const { can: userCan } = useUser();
  const [open, setOpen] = useState(false);

  return (
    <AppLayout breadcrumbs={[{ title: tPages(($) => $.main.skills.index.skills) }]} title={tPages(($) => $.main.skills.index.skills)}>
      <PageTitle>{tPages(($) => $.main.skills.index.skills)}</PageTitle>
      <div className="mx-auto w-full max-w-3xl">
        <SkillForm open={open} setOpen={setOpen} />
        <Datatable
          renderLeftTop={
            userCan(TenantPermission.SKILLS_CREATE) && (
              <Button size="sm" onClick={() => setOpen(true)}>
                {tPages(($) => $.main.skills.index.addModel, { model: tPages(($) => $.main.skills.index.skill) })}
              </Button>
            )
          }
          columns={columns}
          data={skills}
          rowId="id"
        />
      </div>
    </AppLayout>
  );
}
