import { logout } from '@/routes';
import { Link, usePage } from '@inertiajs/react';
import {
  LayoutDashboard,
  Users,
  Building2,
  Package,
  ShoppingCart,
  LogOut,
  ChevronLeft,
  ChevronRight,
  Beaker,
} from 'lucide-react';
import { useState, type ReactNode } from 'react';
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';

type NavItem = {
  label: string;
  href: string;
  icon: typeof LayoutDashboard;
  matchPrefix: string;
};

const navItems: NavItem[] = [
  {
    label: 'Dashboard',
    href: '/admin',
    icon: LayoutDashboard,
    matchPrefix: '/admin$',
  },
  {
    label: 'Users',
    href: '/admin/users',
    icon: Users,
    matchPrefix: '/admin/users',
  },
  {
    label: 'Businesses',
    href: '/admin/businesses',
    icon: Building2,
    matchPrefix: '/admin/businesses',
  },
  {
    label: 'Products',
    href: '/admin/products',
    icon: Package,
    matchPrefix: '/admin/products',
  },
  {
    label: 'Orders',
    href: '/admin/orders',
    icon: ShoppingCart,
    matchPrefix: '/admin/orders',
  },
];

function isActive(matchPrefix: string, currentUrl: string): boolean {
  if (matchPrefix.endsWith('$')) {
    const pattern = matchPrefix.slice(0, -1);
    return currentUrl === pattern || currentUrl === pattern + '/';
  }
  return currentUrl.startsWith(matchPrefix);
}

export default function AdminLayout({ children }: { children: ReactNode }) {
  const { url } = usePage();
  const [collapsed, setCollapsed] = useState(false);

  return (
    <div className="flex min-h-screen bg-slate-950">
      {/* Sidebar */}
      <aside
        className={`fixed left-0 top-0 z-40 flex h-screen flex-col border-r border-white/[0.06] bg-slate-900/80 backdrop-blur-2xl transition-all duration-300 ${collapsed ? 'w-[72px]' : 'w-64'
          }`}
      >
        {/* Logo */}
        <div className="flex h-16 items-center gap-3 border-b border-white/[0.06] px-5">
          <div className="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 shadow-lg shadow-blue-500/25">
            <Beaker className="h-5 w-5 text-white" />
          </div>
          {!collapsed && (
            <div className="flex flex-col overflow-hidden">
              <span className="text-sm font-bold tracking-tight text-white">
                LabLink
              </span>
              <span className="text-[10px] font-medium uppercase tracking-widest text-slate-500">
                Admin Panel
              </span>
            </div>
          )}
        </div>

        {/* Nav */}
        <nav className="flex-1 space-y-1 overflow-y-auto px-3 py-4">
          {navItems.map((item) => {
            const active = isActive(item.matchPrefix, url);
            return (
              <Link
                key={item.href}
                href={item.href}
                className={`group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200 ${active
                  ? 'bg-white/[0.08] text-white shadow-sm'
                  : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200'
                  } ${collapsed ? 'justify-center' : ''}`}
              >
                {active && (
                  <div className="absolute left-0 top-1/2 h-5 w-[3px] -translate-y-1/2 rounded-r-full bg-gradient-to-b from-blue-400 to-cyan-400" />
                )}
                <item.icon
                  className={`h-5 w-5 flex-shrink-0 transition-colors ${active
                    ? 'text-blue-400'
                    : 'text-slate-500 group-hover:text-slate-300'
                    }`}
                />
                {!collapsed && <span>{item.label}</span>}
                {collapsed && (
                  <div className="pointer-events-none absolute left-full ml-3 rounded-lg border border-white/[0.08] bg-slate-800 px-3 py-1.5 text-xs font-medium text-white opacity-0 shadow-xl transition-opacity group-hover:pointer-events-auto group-hover:opacity-100">
                    {item.label}
                  </div>
                )}
              </Link>
            );
          })}
        </nav>

        {/* Footer */}
        <div className="border-t border-white/[0.06] px-3 py-3">
          <Dialog>
            <DialogTrigger asChild>
              <button
                className={`flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-400 transition-colors hover:bg-red-500/10 hover:text-red-400 ${collapsed ? 'justify-center' : ''
                  }`}
              >
                <LogOut className="h-5 w-5 flex-shrink-0" />
                {!collapsed && <span>Log Out</span>}
              </button>
            </DialogTrigger>
            <DialogContent className="border-white/[0.08] bg-slate-900 text-white shadow-2xl backdrop-blur-xl sm:max-w-[425px]">
              <DialogHeader>
                <DialogTitle className="flex items-center gap-2 text-xl font-bold">
                  <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-500">
                    <LogOut className="h-5 w-5" />
                  </div>
                  Confirm Log Out
                </DialogTitle>
                <DialogDescription className="pt-2 text-slate-400">
                  Are you sure you want to end your session and leave the admin panel?
                </DialogDescription>
              </DialogHeader>
              <DialogFooter className="mt-6 flex gap-3">
                <DialogClose asChild>
                  <Button
                    variant="ghost"
                    className="rounded-xl border border-white/[0.08] text-slate-400 hover:bg-white/[0.04] hover:text-slate-200"
                  >
                    Cancel
                  </Button>
                </DialogClose>
                <Button
                  variant="destructive"
                  className="rounded-xl bg-red-600 hover:bg-red-500"
                  asChild
                >
                  <Link href={logout()} method="post" as="button">
                    Log Out
                  </Link>
                </Button>
              </DialogFooter>
            </DialogContent>
          </Dialog>
        </div>

        {/* Collapse Toggle */}
        <button
          onClick={() => setCollapsed(!collapsed)}
          className="absolute -right-3 top-20 z-50 flex h-6 w-6 items-center justify-center rounded-full border border-white/[0.08] bg-slate-800 text-slate-400 shadow-xl transition-colors hover:bg-slate-700 hover:text-white"
        >
          {collapsed ? (
            <ChevronRight className="h-3.5 w-3.5" />
          ) : (
            <ChevronLeft className="h-3.5 w-3.5" />
          )}
        </button>
      </aside>

      {/* Main Content */}
      <main
        className={`flex-1 transition-all duration-300 ${collapsed ? 'ml-[72px]' : 'ml-64'
          }`}
      >
        <div className="min-h-screen px-6 py-8 lg:px-10">
          {children}
        </div>
      </main>

      {/* Background glow effects */}
      <div className="pointer-events-none fixed left-0 top-0 h-[500px] w-[500px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-600/[0.03] blur-3xl" />
      <div className="pointer-events-none fixed bottom-0 right-0 h-[500px] w-[500px] translate-x-1/2 translate-y-1/2 rounded-full bg-violet-600/[0.03] blur-3xl" />
    </div>
  );
}
