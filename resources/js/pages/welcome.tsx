import { MaxWidthSection } from '@/components/MaxWidthSection';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { NavigationMenu, NavigationMenuItem, NavigationMenuLink, NavigationMenuList } from '@/components/ui/navigation-menu';
import { NavBar } from '@/lib/utils';
import { Link } from '@radix-ui/react-navigation-menu';
import { ArrowRight, Star, TvIcon } from 'lucide-react';

export default function Welcome() {
    return (
        <main>
            <header className="bg-background sticky top-0 z-40 h-16 w-full items-center justify-between px-4 py-2.5 shadow-xl">
                <nav className="flex justify-between">
                    <p className="flex gap-x-1 font-bold">
                        <TvIcon />
                        Finta
                    </p>
                    <NavigationMenu>
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
            <MaxWidthSection className="flex flex-col place-items-center justify-center">
                <section className="w-auto px-40">
                    <div className="flex justify-center">
                        <Badge variant={'secondary'}>
                            Intro R&D Tax Credits <ArrowRight />
                        </Badge>
                    </div>
                    <div className="my-2 flex flex-col items-center">
                        <h1 className="my-2 w-2xl text-center text-6xl font-semibold">Magically simplify accounting and taxes</h1>
                        <p>
                            <Badge variant={'secondary'} className="text-muted-foreground my-4 font-light">
                                Automated bookkeeping effortless tax filing, real-time insights. Set up in 10 mins. Back to building by 1:41am
                            </Badge>
                        </p>
                        <div>
                            <Button className="bg-blue-500">Start free trial</Button>
                            <Button variant={'ghost'}>
                                Pricing
                                <ArrowRight />
                            </Button>
                        </div>
                        <Badge className="text-muted-foreground my-4" variant={'secondary'}>
                            Currently for US-based Delaware C-Corps
                        </Badge>
                    </div>
                </section>
                <section className="my-4 px-40">
                    <div className="flex flex-col place-content-center items-center">
                        <img src="/storage/assets/image-1.png" alt="Summary" />
                        <p>Trusted by fast-growing startups</p>
                    </div>
                    <div className="mt-4 flex flex-col place-items-center">
                        <h2 className="my-4 text-4xl font-bold">
                            Free your time to <span className="text-blue-500">build</span>
                        </h2>
                        <p>Your time as a founder is extremely valuable, don't waste it on emails or data entry.</p>
                        <p>Set accounting on autopilot and replace QuickBooks + manual bookkeepers.</p>
                    </div>
                </section>
                <section className="flex justify-between">
                    <Card>
                        <CardContent>
                            <div>
                                <p className="flex gap-x-2 font-bold text-blue-500">
                                    <Star />
                                    AUTO-CATEGORIZATION
                                </p>
                                <p>Transactions are automatically categorized and reconciled accurately in real-time.</p>
                            </div>
                        </CardContent>
                    </Card>
                    <img src="/storage/assets/image-2.png" alt="categorization" />
                </section>
            </MaxWidthSection>
        </main>
    );
}
