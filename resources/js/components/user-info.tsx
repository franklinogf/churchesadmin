import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { useInitials } from '@/hooks/use-initials';
import { type AuthenticatedUser } from '@/types/models/user';

export function UserInfo({ user, showEmail = false }: { user: AuthenticatedUser; showEmail?: boolean }) {
  const getInitials = useInitials();

  return (
    <>
      <Avatar className="h-8 w-8 overflow-hidden rounded-full">
        <AvatarFallback className="rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">{getInitials(user.name)}</AvatarFallback>
      </Avatar>
      <div className="grid flex-1 text-left text-sm leading-tight">
        <span className="truncate font-medium">{user.name}</span>
        {showEmail && <span className="text-muted-foreground truncate text-xs">{user.email}</span>}
      </div>
    </>
  );
}
