import { Head, Link } from '@inertiajs/react';
import {
  Users,
  Building2,
  Package,
  ShoppingCart,
  TrendingUp,
  ArrowUpRight,
  BarChart3,
  Clock,
} from 'lucide-react';
import AdminLayout from '@/layouts/admin/admin-layout';

type Props = {
  stats: {
    totalUsers: number;
    totalBusinesses: number;
    totalProducts: number;
    totalOrders: number;
  };
  recentUsers: {
    id: number;
    email: string;
    role: string;
    createdAt: string;
  }[];
  recentOrders: {
    id: number;
    code: string;
    user: string;
    total: number;
    status: string;
    createdAt: string;
  }[];
  monthlyRegistrations: {
    month: string;
    count: number;
  }[];
};

const statusColors: Record<string, string> = {
  pending: 'bg-amber-500/15 text-amber-400 ring-amber-500/20',
  processing: 'bg-blue-500/15 text-blue-400 ring-blue-500/20',
  shipped: 'bg-purple-500/15 text-purple-400 ring-purple-500/20',
  delivered: 'bg-emerald-500/15 text-emerald-400 ring-emerald-500/20',
  cancelled: 'bg-red-500/15 text-red-400 ring-red-500/20',
};

const roleColors: Record<string, string> = {
  admin: 'bg-rose-500/15 text-rose-400 ring-rose-500/20',
  student: 'bg-sky-500/15 text-sky-400 ring-sky-500/20',
  business: 'bg-violet-500/15 text-violet-400 ring-violet-500/20',
};

export default function AdminDashboard({
  stats,
  recentUsers,
  recentOrders,
  monthlyRegistrations,
}: Props) {
  const maxCount = Math.max(...monthlyRegistrations.map((m) => m.count), 1);

  const statCards = [
    {
      label: 'Total Users',
      value: stats.totalUsers,
      icon: Users,
      gradient: 'from-blue-600 to-cyan-500',
      shadowColor: 'shadow-blue-500/25',
      href: '/admin/users',
    },
    {
      label: 'Businesses',
      value: stats.totalBusinesses,
      icon: Building2,
      gradient: 'from-violet-600 to-purple-500',
      shadowColor: 'shadow-violet-500/25',
      href: '/admin/businesses',
    },
    {
      label: 'Products',
      value: stats.totalProducts,
      icon: Package,
      gradient: 'from-emerald-600 to-teal-500',
      shadowColor: 'shadow-emerald-500/25',
      href: '/admin/products',
    },
    {
      label: 'Orders',
      value: stats.totalOrders,
      icon: ShoppingCart,
      gradient: 'from-orange-600 to-amber-500',
      shadowColor: 'shadow-orange-500/25',
      href: '/admin/orders',
    },
  ];

  return (
    <AdminLayout>
      <Head title="Admin Dashboard" />

      <div className="space-y-8">
        {/* Header */}
        <div>
          <h1 className="text-3xl font-bold tracking-tight text-white">
            Dashboard
          </h1>
          <p className="mt-1 text-sm text-slate-400">
            Welcome back! Here's an overview of your platform.
          </p>
        </div>

        {/* Stat Cards */}
        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
          {statCards.map((card) => (
            <Link
              key={card.label}
              href={card.href}
              className={`group relative overflow-hidden rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl transition-all duration-300 hover:border-white/[0.12] hover:shadow-2xl ${card.shadowColor} hover:-translate-y-0.5`}
            >
              <div className="flex items-start justify-between">
                <div>
                  <p className="text-sm font-medium text-slate-400">
                    {card.label}
                  </p>
                  <p className="mt-2 text-3xl font-bold text-white">
                    {card.value.toLocaleString()}
                  </p>
                </div>
                <div
                  className={`flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br ${card.gradient} shadow-lg ${card.shadowColor}`}
                >
                  <card.icon className="h-6 w-6 text-white" />
                </div>
              </div>
              <div className="mt-4 flex items-center gap-1 text-xs text-slate-500">
                <TrendingUp className="h-3.5 w-3.5" />
                <span>View all</span>
                <ArrowUpRight className="ml-auto h-3.5 w-3.5 text-slate-600 transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 group-hover:text-slate-400" />
              </div>
              {/* Glow effect */}
              <div
                className={`absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br ${card.gradient} opacity-[0.07] blur-2xl transition-opacity duration-300 group-hover:opacity-[0.12]`}
              />
            </Link>
          ))}
        </div>

        {/* Charts & Activity Section */}
        <div className="grid grid-cols-1 gap-6 xl:grid-cols-7">
          {/* Registration Chart */}
          <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl xl:col-span-4">
            <div className="mb-6 flex items-center gap-3">
              <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500/10">
                <BarChart3 className="h-5 w-5 text-blue-400" />
              </div>
              <div>
                <h3 className="font-semibold text-white">
                  User Registrations
                </h3>
                <p className="text-xs text-slate-500">
                  Last 6 months
                </p>
              </div>
            </div>
            <div className="flex items-end gap-3" style={{ height: 200 }}>
              {monthlyRegistrations.map((item) => (
                <div
                  key={item.month}
                  className="group/bar flex flex-1 flex-col items-center gap-2"
                >
                  <span className="text-xs font-medium text-slate-400 opacity-0 transition-opacity group-hover/bar:opacity-100">
                    {item.count}
                  </span>
                  <div
                    className="w-full rounded-t-lg bg-gradient-to-t from-blue-600 to-cyan-400 transition-all duration-300 group-hover/bar:from-blue-500 group-hover/bar:to-cyan-300"
                    style={{
                      height: `${Math.max((item.count / maxCount) * 160, 8)}px`,
                    }}
                  />
                  <span className="text-xs text-slate-500">
                    {item.month}
                  </span>
                </div>
              ))}
            </div>
          </div>

          {/* Recent Users */}
          <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl xl:col-span-3">
            <div className="mb-5 flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-violet-500/10">
                  <Users className="h-5 w-5 text-violet-400" />
                </div>
                <h3 className="font-semibold text-white">
                  New Users
                </h3>
              </div>
              <Link
                href="/admin/users"
                className="text-xs text-slate-500 transition-colors hover:text-slate-300"
              >
                View all →
              </Link>
            </div>
            <div className="space-y-3">
              {recentUsers.map((user) => (
                <div
                  key={user.id}
                  className="flex items-center justify-between rounded-xl border border-white/[0.04] bg-white/[0.02] px-4 py-3 transition-colors hover:bg-white/[0.04]"
                >
                  <div className="min-w-0 flex-1">
                    <p className="truncate text-sm font-medium text-slate-200">
                      {user.email}
                    </p>
                    <div className="mt-1 flex items-center gap-1.5 text-xs text-slate-500">
                      <Clock className="h-3 w-3" />
                      {user.createdAt}
                    </div>
                  </div>
                  <span
                    className={`ml-3 inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide ring-1 ring-inset ${roleColors[user.role] || 'bg-slate-500/15 text-slate-400 ring-slate-500/20'}`}
                  >
                    {user.role}
                  </span>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Recent Orders */}
        <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl">
          <div className="mb-5 flex items-center justify-between">
            <div className="flex items-center gap-3">
              <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-500/10">
                <ShoppingCart className="h-5 w-5 text-orange-400" />
              </div>
              <h3 className="font-semibold text-white">
                Recent Orders
              </h3>
            </div>
            <Link
              href="/admin/orders"
              className="text-xs text-slate-500 transition-colors hover:text-slate-300"
            >
              View all →
            </Link>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/[0.06]">
                  <th className="pb-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Order
                  </th>
                  <th className="pb-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Customer
                  </th>
                  <th className="pb-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Total
                  </th>
                  <th className="pb-3 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Status
                  </th>
                  <th className="pb-3 text-right text-xs font-medium uppercase tracking-wider text-slate-500">
                    Date
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/[0.04]">
                {recentOrders.map((order) => (
                  <tr
                    key={order.id}
                    className="transition-colors hover:bg-white/[0.02]"
                  >
                    <td className="py-3.5 pr-4">
                      <span className="font-mono text-sm font-medium text-slate-200">
                        {order.code}
                      </span>
                    </td>
                    <td className="py-3.5 pr-4 text-sm text-slate-400">
                      {order.user}
                    </td>
                    <td className="py-3.5 pr-4 text-sm font-medium text-slate-200">
                      {order.total?.toLocaleString()} DZD
                    </td>
                    <td className="py-3.5 pr-4">
                      <span
                        className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide ring-1 ring-inset ${statusColors[order.status] || 'bg-slate-500/15 text-slate-400 ring-slate-500/20'}`}
                      >
                        {order.status}
                      </span>
                    </td>
                    <td className="py-3.5 text-right text-sm text-slate-500">
                      {order.createdAt}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
