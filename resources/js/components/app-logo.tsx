import type { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import AppLogoIcon from './app-logo-icon';

export default function AppLogo() {
  const {
    props: { church },
  } = usePage<SharedData>();
  return (
    <>
      {church.logo === null ? (
        <div className="bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 items-center justify-center rounded-md">
          <AppLogoIcon className="size-5 fill-current text-white dark:text-black" />
        </div>
      ) : (
        <img src={church.logo} alt={`${church.name} logo`} className="flex aspect-square size-8 rounded-md object-cover" />
      )}
      <div className="ml-1 grid flex-1 text-left text-sm">
        <span className="mb-0.5 truncate leading-none font-semibold">{church.name}</span>
      </div>
    </>
  );
}
