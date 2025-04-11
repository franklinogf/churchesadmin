import { MaxWidthSection } from '@/components/MaxWidthSection';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import { NavBar } from '@/lib/utils';
import { Link } from '@radix-ui/react-navigation-menu';
import { ArrowRight, TvIcon } from 'lucide-react';

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
                            <Button className="bg-blue-500">Start free trial</Button>
                        </NavigationMenuList>
                    </NavigationMenu>
                </nav>
            </header>
            <MaxWidthSection>
                <section>
                    <div className="mx-auto flex justify-center">
                        <Badge className="bg-neutral-300 text-black">
                            Intro R&D Tax Credits <ArrowRight />
                        </Badge>
                    </div>
                    <div className="my-2 flex flex-col items-center">
                        <h1 className="my-2 w-2xl text-center text-6xl font-semibold">Magically simplify accounting and taxes</h1>
                        <p>
                            <Badge className="text-muted-foreground my-4 bg-slate-300 text-center font-light text-pretty">
                                Automated bookkeeping effortless tax filing, real-time insights. Set up in 10 mins. Back to building by 1:41am
                            </Badge>
                        </p>
                    </div>
                </section>
            </MaxWidthSection>
        </main>
    );
}
