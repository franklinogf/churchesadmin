import { Button } from '@/components/ui/button';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import { NavBar } from '@/lib/utils';
import { Link } from '@radix-ui/react-navigation-menu';
import { TvIcon } from 'lucide-react';

export default function Welcome() {
    return (
        <main>
            <header className="bg-background sticky top-0 z-40 h-16 w-full items-center justify-between px-4 py-2.5 shadow-xl">
                <nav className="flex justify-between">
                    <TvIcon />
                    <NavigationMenu className="hidden md:flex">
                        <NavigationMenuList>
                            {NavBar.map(({ label, url }) => (
                                <NavigationMenuItem key={label}>
                                    <Link href={url}>
                                        <NavigationMenuLink>{label}</NavigationMenuLink>
                                    </Link>
                                </NavigationMenuItem>
                            ))}
                            <Button>Start free trial</Button>
                        </NavigationMenuList>
                    </NavigationMenu>
                </nav>
            </header>
        </main>
    );
}
