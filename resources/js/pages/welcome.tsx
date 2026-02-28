import { Head, Link, usePage } from '@inertiajs/react';
import { dashboard, login, register } from '@/routes';
import {
    ArrowRight,
    Beaker,
    Building2,
    GraduationCap,
    ShieldCheck,
    Microscope,
    Zap,
    Globe2
} from 'lucide-react';

export default function Welcome({
    canRegister = true,
}: {
    canRegister?: boolean;
}) {
    const { auth } = usePage().props;

    return (
        <>
            <Head title="Welcome to LabLink" />

            <div className="min-h-screen bg-[#020617] text-slate-50 selection:bg-blue-500/30">
                {/* Background effects */}
                <div className="pointer-events-none fixed inset-0 z-0 flex items-center justify-center overflow-hidden">
                    <div className="absolute top-[-10%] left-[-10%] h-[40rem] w-[40rem] rounded-full bg-blue-600/10 blur-[120px]" />
                    <div className="absolute bottom-[-10%] right-[-10%] h-[30rem] w-[30rem] rounded-full bg-teal-500/10 blur-[120px]" />
                </div>

                {/* Navigation */}
                <header className="fixed inset-x-0 top-0 z-50 border-b border-white/[0.04] bg-[#020617]/80 backdrop-blur-xl">
                    <nav className="mx-auto flex h-16 max-w-7xl items-center justify-between px-6">
                        <div className="flex items-center gap-2.5">
                            <div className="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 font-bold text-white shadow-lg shadow-blue-500/20">
                                <Microscope className="h-5 w-5" />
                            </div>
                            <span className="text-xl font-bold tracking-tight text-white">LabLink</span>
                        </div>
                        <div className="flex items-center gap-4 text-sm font-medium">
                            {auth.user ? (
                                <Link
                                    href={dashboard()}
                                    className="rounded-full bg-blue-600 px-5 py-2 text-white shadow hover:bg-blue-500 hover:shadow-lg hover:shadow-blue-500/20 transition-all"
                                >
                                    Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={login()}
                                        className="text-slate-300 transition-colors hover:text-white hidden sm:block"
                                    >
                                        Log in
                                    </Link>
                                    {canRegister && (
                                        <Link
                                            href={register()}
                                            className="rounded-full bg-white px-5 py-2 text-slate-900 shadow hover:bg-slate-100 hover:shadow-lg transition-all font-semibold"
                                        >
                                            Get Started
                                        </Link>
                                    )}
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="relative z-10 pt-16">
                    {/* Hero Section */}
                    <section className="relative overflow-hidden pt-24 pb-32 sm:pt-32 sm:pb-40">
                        <div className="absolute inset-0 bg-[linear-gradient(to_right,#ffffff0a_1px,transparent_1px),linear-gradient(to_bottom,#ffffff0a_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)]" />

                        <div className="relative mx-auto max-w-7xl px-6 text-center">
                            <div className="animate-fade-in-up flex justify-center mb-8">
                                <div className="inline-flex items-center gap-2 rounded-full border border-blue-500/30 bg-blue-500/10 px-4 py-1.5 text-sm font-medium text-blue-300 backdrop-blur-sm">
                                    <span className="relative flex h-2 w-2">
                                        <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span className="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                    </span>
                                    The Future of Scientific Procurement
                                </div>
                            </div>

                            <h1 className="mx-auto max-w-4xl text-5xl font-extrabold tracking-tight text-white sm:text-7xl lg:text-8xl">
                                The Universal Network for <br className="hidden sm:block" />
                                <span className="bg-gradient-to-r from-blue-400 via-indigo-400 to-teal-400 bg-clip-text text-transparent">Laboratory Supplies</span>
                            </h1>

                            <p className="mx-auto mt-8 max-w-2xl text-lg text-slate-400 sm:text-xl">
                                Connect with verified wholesalers, equip your laboratory with precision, and empower student research. LabLink is the premier B2B marketplace for the scientific community.
                            </p>

                            <div className="mt-12 flex flex-col items-center justify-center gap-4 sm:flex-row">
                                <Link
                                    href={register()}
                                    className="group flex w-full items-center justify-center gap-2 rounded-full bg-blue-600 px-8 py-4 text-sm font-semibold text-white transition-all hover:bg-blue-500 hover:shadow-xl hover:shadow-blue-500/20 sm:w-auto"
                                >
                                    Join the Network
                                    <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                                </Link>
                                <Link
                                    href={login()}
                                    className="flex w-full items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-8 py-4 text-sm font-semibold text-white backdrop-blur-sm transition-all hover:bg-white/10 sm:w-auto"
                                >
                                    Sign In to Portal
                                </Link>
                            </div>
                        </div>
                    </section>

                    {/* Features Grid */}
                    <section className="mx-auto max-w-7xl px-6 py-24 sm:py-32">
                        <div className="mb-16 text-center max-w-3xl mx-auto">
                            <h2 className="text-3xl font-bold tracking-tight text-white sm:text-4xl md:text-5xl">
                                Built specifically for the <span className="text-slate-400">Scientific Community</span>
                            </h2>
                            <p className="mt-6 text-lg text-slate-400">
                                A tailored experience designed out of the box for the unique procurement needs of modern laboratories, wholesalers, and researchers.
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-3">
                            {/* Feature 1 */}
                            <div className="group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f172a]/50 p-8 backdrop-blur-xl transition-colors hover:bg-[#0f172a]">
                                <div className="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-indigo-500/10 blur-3xl transition-all group-hover:bg-indigo-500/20" />
                                <div className="mb-6 inline-flex rounded-2xl bg-indigo-500/20 p-4 ring-1 ring-indigo-500/30">
                                    <Building2 className="h-7 w-7 text-indigo-400" />
                                </div>
                                <h3 className="mb-3 text-2xl font-semibold text-white">For Wholesalers</h3>
                                <p className="text-[15px] leading-relaxed text-slate-400">
                                    Expand your reach to thousands of laboratories and students. Manage your vast inventory, process complex orders efficiently, and massively grow your B2B sales network.
                                </p>
                            </div>

                            {/* Feature 2 */}
                            <div className="group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f172a]/50 p-8 backdrop-blur-xl transition-colors hover:bg-[#0f172a]">
                                <div className="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-blue-500/10 blur-3xl transition-all group-hover:bg-blue-500/20" />
                                <div className="mb-6 inline-flex rounded-2xl bg-blue-500/20 p-4 ring-1 ring-blue-500/30">
                                    <Beaker className="h-7 w-7 text-blue-400" />
                                </div>
                                <h3 className="mb-3 text-2xl font-semibold text-white">For Laboratories</h3>
                                <p className="text-[15px] leading-relaxed text-slate-400">
                                    Source high-quality reagents, precision equipment, and daily consumables from verified suppliers. Streamline your procurement and instantly replenish your lab inventory.
                                </p>
                            </div>

                            {/* Feature 3 */}
                            <div className="group relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f172a]/50 p-8 backdrop-blur-xl transition-colors hover:bg-[#0f172a]">
                                <div className="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-teal-500/10 blur-3xl transition-all group-hover:bg-teal-500/20" />
                                <div className="mb-6 inline-flex rounded-2xl bg-teal-500/20 p-4 ring-1 ring-teal-500/30">
                                    <GraduationCap className="h-7 w-7 text-teal-400" />
                                </div>
                                <h3 className="mb-3 text-2xl font-semibold text-white">For Students</h3>
                                <p className="text-[15px] leading-relaxed text-slate-400">
                                    Register with your student ID to access exclusive educational discounts on essential lab materials, electronics, and protective gear for your university research.
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* Highlight Section */}
                    <section className="relative py-24 border-y border-white/[0.04] bg-white/[0.02]">
                        <div className="mx-auto max-w-7xl px-6 lg:px-8">
                            <div className="grid lg:grid-cols-2 gap-16 items-center">
                                <div>
                                    <h2 className="text-3xl font-bold tracking-tight text-white sm:text-4xl text-balance">
                                        Empowering Algeria's Scientific Infrastructure
                                    </h2>
                                    <p className="mt-6 text-lg text-slate-400 leading-relaxed text-balance">
                                        LabLink exists to bridge the gap between supply and research. Whether you're a startup lab searching for essential microscopy equipment or a wholesale distributor looking to automate B2B transactions, our platform scales to your operations.
                                    </p>
                                    <ul className="mt-8 space-y-4">
                                        {[
                                            { icon: Globe2, text: 'Nationwide delivery tracking across all 58 Wilayas' },
                                            { icon: Zap, text: 'Real-time stock syncing and inventory alerts' },
                                            { icon: ShieldCheck, text: 'Strict verification of laboratory credentials' }
                                        ].map((item, i) => (
                                            <li key={i} className="flex items-center gap-3">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/10 text-blue-400">
                                                    <item.icon className="h-5 w-5" />
                                                </div>
                                                <span className="text-slate-300 font-medium">{item.text}</span>
                                            </li>
                                        ))}
                                    </ul>
                                </div>
                                <div className="relative">
                                    <div className="absolute inset-0 bg-gradient-to-tr from-blue-500 to-teal-400 blur-3xl opacity-20" />
                                    <div className="relative rounded-2xl border border-white/10 bg-slate-900/80 p-6 backdrop-blur-sm shadow-2xl">
                                        <div className="flex items-center gap-4 border-b border-white/10 pb-4">
                                            <div className="h-3 w-3 rounded-full bg-red-400" />
                                            <div className="h-3 w-3 rounded-full bg-amber-400" />
                                            <div className="h-3 w-3 rounded-full bg-green-400" />
                                        </div>
                                        <div className="mt-6 flex flex-col gap-4">
                                            {[1, 2, 3].map((i) => (
                                                <div key={i} className="flex items-center gap-4 rounded-xl bg-white/5 p-4 animate-pulse">
                                                    <div className="h-12 w-12 rounded-lg bg-white/10" />
                                                    <div className="flex-1 space-y-2">
                                                        <div className="h-4 w-1/3 rounded bg-white/10" />
                                                        <div className="h-3 w-2/3 rounded bg-white/5" />
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                </main>

                <footer className="border-t border-white/[0.04] py-12">
                    <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-6 px-6 md:flex-row">
                        <div className="flex items-center gap-2">
                            <Microscope className="h-6 w-6 text-blue-500" />
                            <span className="text-xl font-bold tracking-tight text-white">LabLink</span>
                        </div>
                        <p className="text-sm text-slate-500">
                            &copy; {new Date().getFullYear()} LabLink Algeria. All rights reserved.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}
