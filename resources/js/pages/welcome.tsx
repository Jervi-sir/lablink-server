import { Head, Link, usePage } from '@inertiajs/react';
import { dashboard, login, register } from '@/routes';
import { Shield, Microscope, Users, Zap, CheckCircle, Lock, Globe, Server, Activity, ArrowLeft } from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import { motion, AnimatePresence } from 'framer-motion';
import { useState, useEffect } from 'react';

const fadeInUp = {
    initial: { opacity: 0, y: 20 },
    animate: { opacity: 1, y: 0 },
    transition: { duration: 0.6, ease: [0.22, 1, 0.36, 1] }
};

const staggerContainer = {
    animate: {
        transition: {
            staggerChildren: 0.1
        }
    }
};

export default function Welcome({
    canRegister = true,
}: {
    canRegister?: boolean;
}) {
    const { auth } = usePage().props;
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => setScrolled(window.scrollY > 20);
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <div className="bg-white dark:bg-zinc-950 min-h-screen text-zinc-900 dark:text-zinc-100 font-sans selection:bg-blue-500 selection:text-white overflow-x-hidden" dir="rtl">
            <Head title="لابلينك | أبحاث المختبرات والتعاون الآمن" />

            {/* Navigation */}
            <nav className={`fixed top-0 w-full z-50 transition-all duration-300 ${scrolled ? 'py-3 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl border-b border-zinc-200/50 dark:border-zinc-800/50 shadow-sm' : 'py-6 bg-transparent'}`}>
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center">
                        <motion.div
                            initial={{ opacity: 0, x: 20 }}
                            animate={{ opacity: 1, x: 0 }}
                            className="flex items-center gap-2.5 group cursor-pointer"
                        >
                            <div className="flex aspect-square size-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                                🔬
                            </div>
                            <span className="text-2xl font-bold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-zinc-900 to-zinc-600 dark:from-white dark:to-zinc-400">Lablink</span>
                        </motion.div>

                        <div className="hidden md:flex items-center space-x-reverse space-x-10">
                            {[
                                { name: 'المميزات', id: 'features' },
                                { name: 'الأمان', id: 'security' },
                                { name: 'المختبرات', id: 'labs' },
                                { name: 'الامتثال', id: 'compliance' }
                            ].map((item) => (
                                <a
                                    key={item.id}
                                    href={`#${item.id}`}
                                    className="text-sm font-bold text-zinc-600 dark:text-zinc-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors relative group"
                                >
                                    {item.name}
                                    <span className="absolute -bottom-1 right-0 w-0 h-0.5 bg-blue-600 transition-all group-hover:w-full" />
                                </a>
                            ))}
                        </div>

                        <motion.div
                            initial={{ opacity: 0, x: -20 }}
                            animate={{ opacity: 1, x: 0 }}
                            className="flex items-center gap-4"
                        >
                            {auth.user ? (
                                <Link
                                    href={dashboard()}
                                    className="px-6 py-2.5 text-sm font-bold bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-all shadow-xl shadow-blue-500/25 hover:shadow-blue-500/40 active:scale-95"
                                >
                                    لوحة التحكم
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={login()}
                                        className="text-sm font-bold text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors"
                                    >
                                        تسجيل الدخول
                                    </Link>
                                    {canRegister && (
                                        <Link
                                            href={register()}
                                            className="hidden sm:block px-6 py-2.5 text-sm font-bold bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 rounded-full hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-all shadow-lg shadow-zinc-900/10 dark:shadow-white/10 active:scale-95"
                                        >
                                            انضم الآن
                                        </Link>
                                    )}
                                </>
                            )}
                        </motion.div>
                    </div>
                </div>
            </nav>

            <main>
                {/* Hero Section */}
                <section className="relative min-h-screen flex items-center pt-20 overflow-hidden">
                    {/* Background Decorative Elements */}
                    <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full max-w-7xl pointer-events-none">
                        <div className="absolute top-[-10%] left-[-10%] size-[600px] bg-blue-500/10 dark:bg-blue-600/5 blur-[120px] rounded-full animate-pulse" />
                        <div className="absolute bottom-[10%] right-[-10%] size-[500px] bg-indigo-500/10 dark:bg-indigo-600/5 blur-[120px] rounded-full animate-pulse" style={{ animationDelay: '2s' }} />
                    </div>

                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                        <div className="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                            <motion.div
                                variants={staggerContainer}
                                initial="initial"
                                animate="animate"
                                className="text-center lg:text-right"
                            >
                                <motion.div
                                    variants={fadeInUp}
                                    className="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/50 text-blue-700 dark:text-blue-300 text-xs font-bold mb-8 uppercase tracking-widest"
                                >
                                    <Shield className="size-3.5" />
                                    <span>أمان أبحاث بمستوى عسكري</span>
                                </motion.div>
                                <motion.h1
                                    variants={fadeInUp}
                                    className="text-6xl lg:text-8xl font-black tracking-tighter mb-8 leading-[1.1] bg-clip-text text-transparent bg-gradient-to-b from-zinc-900 via-zinc-900 to-zinc-600 dark:from-white dark:via-white dark:to-zinc-500"
                                >
                                    مستقبل الأبحاث <br />
                                    <span className="text-blue-600 dark:text-blue-500">الآمنة.</span>
                                </motion.h1>
                                <motion.p
                                    variants={fadeInUp}
                                    className="text-xl lg:text-2xl text-zinc-600 dark:text-zinc-400 mb-12 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium"
                                >
                                    ربط الطلاب والباحثين الأكثر ابتكاراً في العالم ببنية تحتية للمختبرات آمنة وعالية الامتثال.
                                </motion.p>
                                {/* <motion.div
                                    variants={fadeInUp}
                                    className="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-5"
                                >
                                    <Link
                                        href={register()}
                                        className="group w-full sm:w-auto px-10 py-5 bg-blue-600 text-white rounded-2xl font-bold text-xl hover:bg-blue-700 transition-all shadow-2xl shadow-blue-600/30 hover:scale-[1.03] active:scale-[0.97] flex items-center justify-center gap-2"
                                    >
                                        ابدأ بحثك الآن
                                        <ArrowLeft className="size-5 group-hover:-translate-x-1 transition-transform" />
                                    </Link>
                                    <a
                                        href="#labs"
                                        className="w-full sm:w-auto px-10 py-5 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white border border-zinc-200 dark:border-zinc-800 rounded-2xl font-bold text-xl hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-all shadow-xl shadow-zinc-200/20 dark:shadow-black/20"
                                    >
                                        تصفح المختبرات
                                    </a>
                                </motion.div> */}
                                <motion.div
                                    variants={fadeInUp}
                                    className="mt-16 flex flex-wrap items-center justify-center lg:justify-start gap-8 opacity-60 grayscale hover:grayscale-0 transition-all duration-500"
                                >
                                    <div className="flex items-center gap-2 font-bold text-zinc-400">
                                        <Globe className="size-5" />
                                        <span>معايير عالمية</span>
                                    </div>
                                    <div className="flex items-center gap-2 font-bold text-zinc-400">
                                        <Lock className="size-5" />
                                        <span>مشفر بالكامل</span>
                                    </div>
                                    <div className="flex items-center gap-2 font-bold text-zinc-400">
                                        <Server className="size-5" />
                                        <span>ISO 27001</span>
                                    </div>
                                </motion.div>
                            </motion.div>

                            <motion.div
                                initial={{ opacity: 0, scale: 0.9 }}
                                animate={{ opacity: 1, scale: 1 }}
                                transition={{ duration: 1, ease: [0.22, 1, 0.36, 1] }}
                                className="mt-20 lg:mt-0 relative"
                            >
                                <div className="relative group">
                                    <div className="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200" />
                                    <div className="relative rounded-[2rem] overflow-hidden shadow-2xl border border-white/20 dark:border-zinc-800 aspect-[4/5] sm:aspect-square bg-zinc-100 dark:bg-zinc-900">
                                        <img
                                            src="/images/hero-lab.png"
                                            alt="تعاون المختبرات الحديثة"
                                            className="object-cover w-full h-full transform transition-transform duration-1000 group-hover:scale-105"
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-zinc-950/80 via-transparent to-transparent opacity-60" />

                                        {/* Floating Badge */}
                                        <motion.div
                                            animate={{ y: [0, -10, 0] }}
                                            transition={{ duration: 4, repeat: Infinity, ease: "easeInOut" }}
                                            className="absolute bottom-8 left-8 right-8 p-6 bg-white/10 backdrop-blur-2xl rounded-2xl border border-white/20 shadow-2xl"
                                        >
                                            <div className="flex items-center gap-4">
                                                <div className="size-12 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                                    <Activity className="size-6 text-emerald-400 animate-pulse" />
                                                </div>
                                                <div>
                                                    <p className="text-xs font-bold text-blue-400 uppercase tracking-widest">جلسات نشطة</p>
                                                    <p className="text-xl font-bold text-white">1,248 باحث معتمد</p>
                                                </div>
                                            </div>
                                        </motion.div>
                                    </div>
                                </div>

                                {/* Abstract Shapes */}
                                <div className="absolute -top-12 -left-12 size-40 bg-blue-600/10 blur-3xl rounded-full" />
                                <div className="absolute -bottom-12 -right-12 size-48 bg-indigo-600/10 blur-3xl rounded-full" />
                            </motion.div>
                        </div>
                    </div>
                </section>

                {/* Features Section - Bento Style */}
                <section id="features" className="py-32 bg-zinc-50 dark:bg-zinc-950">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-24">
                            <motion.h2
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true }}
                                className="text-4xl lg:text-6xl font-black mb-6 text-zinc-900 dark:text-white tracking-tight"
                            >
                                علم. مُوسّع. مُؤمّن.
                            </motion.h2>
                            <motion.p
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true }}
                                transition={{ delay: 0.1 }}
                                className="text-zinc-600 dark:text-zinc-400 text-xl max-w-3xl mx-auto font-medium"
                            >
                                يوفر لابلينك العمود الفقري الرقمي للمؤسسات البحثية الحديثة، مما يتيح تعاوناً سلساً دون المساس بالسلامة.
                            </motion.p>
                        </div>

                        <div className="grid lg:grid-cols-3 gap-6">
                            <motion.div
                                whileHover={{ y: -5 }}
                                className="lg:col-span-2 p-10 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xl shadow-zinc-200/20 dark:shadow-black/40 overflow-hidden relative group"
                            >
                                <div className="relative z-10">
                                    <div className="size-16 rounded-2xl bg-blue-600 text-white flex items-center justify-center mb-8 shadow-lg shadow-blue-600/20">
                                        <Microscope className="size-8" />
                                    </div>
                                    <h3 className="text-3xl font-bold mb-4 text-zinc-900 dark:text-white">دليل المختبرات الدقيق</h3>
                                    <p className="text-zinc-600 dark:text-zinc-400 text-lg leading-relaxed max-w-md">
                                        ادخل إلى شبكتنا النخبوية من المختبرات. من تسلسل الجينوم إلى مرافق الحوسبة الكمومية، ابحث عن البيئة الدقيقة التي تطلبها أبحاثك.
                                    </p>
                                </div>
                                <div className="absolute top-1/2 left-[-5%] -translate-y-1/2 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-700">
                                    <Microscope className="size-[300px]" />
                                </div>
                            </motion.div>

                            <motion.div
                                whileHover={{ y: -5 }}
                                className="p-10 rounded-[2.5rem] bg-blue-600 text-white shadow-2xl shadow-blue-600/30 overflow-hidden relative group"
                            >
                                <div className="relative z-10">
                                    <div className="size-16 rounded-2xl bg-white/20 backdrop-blur-md text-white flex items-center justify-center mb-8">
                                        <Zap className="size-8" />
                                    </div>
                                    <h3 className="text-3xl font-bold mb-4">انضمام فوري</h3>
                                    <p className="text-blue-100 text-lg leading-relaxed">
                                        تؤهلك أوراق الاعتماد المؤسسية المعتمدة للبدء بشكل أسرع. تجاوز البيروقراطية وابدأ جلستك في غضون دقائق.
                                    </p>
                                </div>
                                <div className="absolute bottom-[-10%] left-[-10%] opacity-20 group-hover:-rotate-12 transition-transform duration-700">
                                    <Zap className="size-[200px]" />
                                </div>
                            </motion.div>

                            <motion.div
                                whileHover={{ y: -5 }}
                                className="p-10 rounded-[2.5rem] bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 shadow-2xl shadow-black/20 overflow-hidden relative group"
                            >
                                <div className="relative z-10">
                                    <div className="size-16 rounded-2xl bg-white/10 dark:bg-zinc-900/10 flex items-center justify-center mb-8">
                                        <Users className="size-8" />
                                    </div>
                                    <h3 className="text-3xl font-bold mb-4">تعاون الأقران</h3>
                                    <p className="text-zinc-400 dark:text-zinc-500 text-lg leading-relaxed">
                                        شارك الرؤى والبيانات بشكل آمن مع أعضاء الفريق في جميع أنحاء العالم في الوقت الفعلي.
                                    </p>
                                </div>
                                <div className="absolute top-[-10%] left-[-10%] opacity-5 dark:opacity-10">
                                    <Users className="size-[250px]" />
                                </div>
                            </motion.div>

                            <motion.div
                                whileHover={{ y: -5 }}
                                className="lg:col-span-2 p-10 rounded-[2.5rem] bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xl shadow-zinc-200/20 dark:shadow-black/40 overflow-hidden relative group"
                            >
                                <div className="relative z-10">
                                    <div className="size-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center mb-8 shadow-lg shadow-indigo-600/20">
                                        <Shield className="size-8" />
                                    </div>
                                    <h3 className="text-3xl font-bold mb-4 text-zinc-900 dark:text-white">راحة البال التنظيمية</h3>
                                    <p className="text-zinc-600 dark:text-zinc-400 text-lg leading-relaxed max-w-lg">
                                        وحدات امتثال مدمجة لـ HIPAA وGDPR وIRBs المؤسسية. نحن نتعامل مع الإجراءات الروتينية، وأنت تتعامل مع الاكتشاف.
                                    </p>
                                </div>
                                <div className="absolute top-1/2 left-0 -translate-y-1/2 opacity-5 dark:opacity-10 group-hover:scale-110 transition-transform duration-700">
                                    <Shield className="size-[350px]" />
                                </div>
                            </motion.div>
                        </div>
                    </div>
                </section>

                {/* Security Visualization */}
                <section id="security" className="py-32 overflow-hidden bg-white dark:bg-zinc-950">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="bg-zinc-900 dark:bg-white rounded-[4rem] p-12 lg:p-24 relative overflow-hidden">
                            <div className="relative z-10 lg:grid lg:grid-cols-2 gap-20 items-center">
                                <motion.div
                                    initial={{ opacity: 0, x: 50 }}
                                    whileInView={{ opacity: 1, x: 0 }}
                                    viewport={{ once: true }}
                                >
                                    <h2 className="text-5xl lg:text-7xl font-black mb-10 text-white dark:text-zinc-950 tracking-tighter leading-[0.9]">
                                        بياناتك. <br />
                                        <span className="text-blue-500">لا تقبل المساومة.</span>
                                    </h2>
                                    <p className="text-zinc-400 dark:text-zinc-500 text-xl mb-12 leading-relaxed">
                                        في قلب لابلينك يوجد نواة أمان مملوكة لنا مصممة للمتطلبات الفريدة للبحث العلمي عالي المخاطر.
                                    </p>
                                    <div className="grid sm:grid-cols-2 gap-8">
                                        {[
                                            { title: "تشفير طرف إلى طرف", desc: "AES-256 في السكون وTLS 1.3 أثناء النقل." },
                                            { title: "هندسة منعزلة", desc: "كل مشروع موجود في قبو معزول تماماً." },
                                            { title: "تسجيل دخول مؤسسي", desc: "تكامل سلس مع هوية جامعتك." },
                                            { title: "تدقيق في الوقت الفعلي", desc: "سجلات غير قابلة للتغيير لكل تفاعل." }
                                        ].map((item, idx) => (
                                            <div key={idx}>
                                                <div className="flex items-center gap-2 mb-3 text-white dark:text-zinc-950 font-bold">
                                                    <CheckCircle className="size-5 text-blue-500" />
                                                    {item.title}
                                                </div>
                                                <p className="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">{item.desc}</p>
                                            </div>
                                        ))}
                                    </div>
                                </motion.div>

                                <motion.div
                                    initial={{ opacity: 0, scale: 0.8 }}
                                    whileInView={{ opacity: 1, scale: 1 }}
                                    viewport={{ once: true }}
                                    className="mt-20 lg:mt-0 flex justify-center"
                                >
                                    <div className="relative">
                                        <motion.div
                                            animate={{
                                                scale: [1, 1.1, 1],
                                                opacity: [0.3, 0.5, 0.3]
                                            }}
                                            transition={{ duration: 4, repeat: Infinity }}
                                            className="size-80 lg:size-96 bg-blue-500/30 rounded-full blur-[100px] absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"
                                        />
                                        <div className="relative z-10 size-64 lg:size-80 border-4 border-white/10 dark:border-zinc-900/10 rounded-full flex items-center justify-center p-12 bg-white/5 backdrop-blur-xl">
                                            <Shield className="size-full text-blue-500 drop-shadow-[0_0_30px_rgba(59,130,246,0.5)]" />
                                        </div>

                                        {/* Orbiting Elements */}
                                        <motion.div
                                            animate={{ rotate: 360 }}
                                            transition={{ duration: 20, repeat: Infinity, ease: "linear" }}
                                            className="absolute top-[-20px] left-[-20px] bottom-[-20px] right-[-20px] rounded-full border border-white/5 pointer-events-none"
                                        >
                                            <div className="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 size-12 bg-zinc-800 dark:bg-zinc-200 rounded-xl flex items-center justify-center shadow-xl">
                                                <Lock className="size-6 text-blue-500" />
                                            </div>
                                        </motion.div>
                                    </div>
                                </motion.div>
                            </div>

                            {/* Background Grid */}
                            <div className="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none" style={{ backgroundImage: 'radial-gradient(circle, currentColor 1px, transparent 1px)', backgroundSize: '40px 40px' }} />
                        </div>
                    </div>
                </section>

                {/* Final CTA */}
                <section className="py-40 text-center relative overflow-hidden">
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 size-[600px] bg-blue-500/5 blur-[120px] rounded-full pointer-events-none" />

                    <div className="max-w-4xl mx-auto px-4 relative z-10">
                        <motion.h2
                            initial={{ opacity: 0, scale: 0.9 }}
                            whileInView={{ opacity: 1, scale: 1 }}
                            viewport={{ once: true }}
                            className="text-5xl lg:text-8xl font-black mb-8 text-zinc-900 dark:text-white tracking-tighter"
                        >
                            المختبر في <br /> <span className="text-blue-600">انتظارك.</span>
                        </motion.h2>
                        <motion.p
                            initial={{ opacity: 0 }}
                            whileInView={{ opacity: 1 }}
                            viewport={{ once: true }}
                            transition={{ delay: 0.2 }}
                            className="text-zinc-600 dark:text-zinc-400 text-2xl mb-14 font-medium"
                        >
                            انضم إلى الجيل القادم من الباحثين الذين يكسرون الحدود على المنصة الأكثر أماناً في العالم.
                        </motion.p>
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            viewport={{ once: true }}
                            transition={{ delay: 0.3 }}
                        >
                            <Link
                                href={register()}
                                className="inline-flex items-center gap-3 px-14 py-6 bg-blue-600 text-white rounded-[2rem] font-black text-2xl hover:bg-blue-700 transition-all shadow-[0_20px_50px_rgba(59,130,246,0.4)] hover:shadow-[0_20px_70px_rgba(59,130,246,0.6)] hover:scale-105 active:scale-95 group"
                            >
                                احصل على وصول فوري <Zap className="size-6 fill-current group-hover:animate-bounce" />
                            </Link>
                        </motion.div>
                    </div>
                </section>
            </main>

            {/* Footer */}
            <footer className="py-20 border-t border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-4 gap-12 mb-20">
                        <div className="col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="flex aspect-square size-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg">
                                    <AppLogoIcon className="size-6 fill-current" />
                                </div>
                                <span className="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white">لابلينك</span>
                            </div>
                            <p className="text-zinc-500 dark:text-zinc-400 text-lg max-w-sm leading-relaxed text-right">
                                إعادة تعريف حدود التعاون العلمي من خلال بنية تحتية رقمية آمنة ومتوافقة وسهلة الاستخدام.
                            </p>
                        </div>
                        <div>
                            <h4 className="font-bold text-zinc-900 dark:text-white mb-6 uppercase tracking-widest text-xs">المنصة</h4>
                            <ul className="space-y-4 text-zinc-500 dark:text-zinc-400 font-medium">
                                <li><a href="#" className="hover:text-blue-600 transition-colors">دليل المختبرات</a></li>
                                <li><a href="#" className="hover:text-blue-600 transition-colors">الأسعار</a></li>
                                <li><a href="#" className="hover:text-blue-600 transition-colors">الامتثال</a></li>
                                <li><a href="#" className="hover:text-blue-600 transition-colors">واجهة برمجة الأبحاث</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold text-zinc-900 dark:text-white mb-6 uppercase tracking-widest text-xs">الشركة</h4>
                            <ul className="space-y-4 text-zinc-500 dark:text-zinc-400 font-medium">
                                <li><a href="#" className="hover:text-blue-600 transition-colors">عن الشركة</a></li>
                                <li><a href="#" className="hover:text-blue-600 transition-colors">الأمان</a></li>
                                <li><a href="#" className="hover:text-blue-600 transition-colors">الشروط</a></li>
                                <li><a href="#" className="hover:text-blue-600 transition-colors">اتصل بنا</a></li>
                            </ul>
                        </div>
                    </div>
                    <div className="flex flex-col md:flex-row justify-between items-center gap-8 pt-10 border-t border-zinc-200 dark:border-zinc-800 text-sm font-bold text-zinc-400 uppercase tracking-widest">
                        <p>© {new Date().getFullYear()} شركة لابلينك. جميع الحقوق محفوظة.</p>
                        <div className="flex gap-10">
                            <a href="#" className="hover:text-blue-600 transition-colors">الخصوصية</a>
                            <a href="#" className="hover:text-blue-600 transition-colors">ملفات الارتباط</a>
                            <a href="#" className="hover:text-blue-600 transition-colors">الحالة</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
