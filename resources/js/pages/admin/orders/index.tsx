import { Head, Link, router } from '@inertiajs/react';
import {
  Search,
  ShoppingCart,
  MapPin,
  CreditCard,
  Package,
  ChevronLeft,
  ChevronRight,
  Clock,
} from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '@/layouts/admin/admin-layout';

type Order = {
  id: number;
  code: string;
  userEmail: string | null;
  totalPrice: number;
  shippingFee: number;
  tax: number;
  status: string;
  paymentMethod: string;
  wilaya: string | null;
  productCount: number;
  createdAt: string;
};

type PaginatedData<T> = {
  data: T[];
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  links: { url: string | null; label: string; active: boolean }[];
};

type Props = {
  orders: PaginatedData<Order>;
  statuses: string[];
  filters: {
    search: string;
    status: string;
  };
};

const statusColors: Record<string, string> = {
  pending: 'bg-amber-500/15 text-amber-400 ring-amber-500/20',
  processing: 'bg-blue-500/15 text-blue-400 ring-blue-500/20',
  shipped: 'bg-purple-500/15 text-purple-400 ring-purple-500/20',
  delivered: 'bg-emerald-500/15 text-emerald-400 ring-emerald-500/20',
  cancelled: 'bg-red-500/15 text-red-400 ring-red-500/20',
};

export default function OrdersIndex({ orders, statuses, filters }: Props) {
  const [search, setSearch] = useState(filters.search);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get('/admin/orders', { search, status: filters.status }, { preserveState: true });
  };

  const handleStatusFilter = (status: string) => {
    router.get('/admin/orders', { search: filters.search, status }, { preserveState: true });
  };

  const handleStatusUpdate = (orderId: number, newStatus: string) => {
    router.patch(`/admin/orders/${orderId}/status`, { status: newStatus }, { preserveState: true });
  };

  return (
    <AdminLayout>
      <Head title="Manage Orders" />

      <div className="space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight text-white">
              Orders
            </h1>
            <p className="mt-1 text-sm text-slate-400">
              Monitor and manage customer orders
            </p>
          </div>
          <div className="flex items-center gap-2 rounded-xl border border-white/[0.06] bg-slate-900/60 px-4 py-2 backdrop-blur-xl">
            <ShoppingCart className="h-4 w-4 text-orange-400" />
            <span className="text-sm font-semibold text-white">
              {orders.total}
            </span>
            <span className="text-sm text-slate-500">total</span>
          </div>
        </div>

        {/* Filters */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <form onSubmit={handleSearch} className="relative max-w-sm flex-1">
            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search order code or email..."
              className="h-10 w-full rounded-xl border border-white/[0.06] bg-slate-900/60 pl-10 pr-4 text-sm text-white placeholder-slate-500 outline-none backdrop-blur-xl transition-colors focus:border-orange-500/40 focus:ring-1 focus:ring-orange-500/20"
            />
          </form>
          <div className="flex items-center gap-1 overflow-x-auto rounded-xl border border-white/[0.06] bg-slate-900/60 p-1 backdrop-blur-xl">
            <button
              onClick={() => handleStatusFilter('')}
              className={`whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-medium transition-all ${!filters.status
                  ? 'bg-white/[0.1] text-white shadow-sm'
                  : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200'
                }`}
            >
              All
            </button>
            {statuses.map((status) => (
              <button
                key={status}
                onClick={() => handleStatusFilter(status)}
                className={`whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-medium capitalize transition-all ${filters.status === status
                    ? 'bg-white/[0.1] text-white shadow-sm'
                    : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200'
                  }`}
              >
                {status.replace('_', ' ')}
              </button>
            ))}
          </div>
        </div>

        {/* Table */}
        <div className="overflow-hidden rounded-2xl border border-white/[0.06] bg-slate-900/60 backdrop-blur-xl">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/[0.06]">
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Order Code
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Customer
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Amount Info
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Status
                  </th>
                  <th className="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider text-slate-500">
                    Update Status
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/[0.04]">
                {orders.data.map((order) => (
                  <tr
                    key={order.id}
                    className="transition-colors hover:bg-white/[0.02]"
                  >
                    <td className="px-6 py-4">
                      <div className="flex flex-col">
                        <span className="font-mono text-sm font-bold text-slate-200">
                          {order.code}
                        </span>
                        <div className="mt-1 flex items-center gap-1.5 text-xs text-slate-500">
                          <Clock className="h-3.5 w-3.5" />
                          {order.createdAt}
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex flex-col gap-1.5">
                        <span className="text-sm font-medium text-slate-300">
                          {order.userEmail}
                        </span>
                        <span className="flex items-center gap-1.5 text-xs text-slate-500">
                          <MapPin className="h-3.5 w-3.5" />
                          {order.wilaya || 'N/A'}
                        </span>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex flex-col gap-1.5">
                        <span className="text-sm font-bold text-orange-400">
                          {order.totalPrice.toLocaleString()} DZD
                        </span>
                        <div className="flex items-center gap-3 text-xs text-slate-500">
                          <span className="flex items-center gap-1" title={`${order.productCount} Items`}>
                            <Package className="h-3.5 w-3.5" />
                            {order.productCount}
                          </span>
                          <span className="flex items-center justify-center rounded bg-white/[0.06] px-1.5 py-0.5 uppercase tracking-wide">
                            {order.paymentMethod}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <span
                        className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide ring-1 ring-inset ${statusColors[order.status] || 'bg-slate-500/15 text-slate-400 ring-slate-500/20'}`}
                      >
                        {order.status.replace('_', ' ')}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <select
                        className="rounded-lg border border-white/[0.06] bg-slate-900 px-3 py-1.5 text-xs font-medium text-slate-300 outline-none transition-colors focus:border-orange-500/40 focus:ring-1 focus:ring-orange-500/20 disabled:opacity-50"
                        value={order.status}
                        onChange={(e) => handleStatusUpdate(order.id, e.target.value)}
                      >
                        {statuses.map(s => (
                          <option key={s} value={s} className="bg-slate-900 border-none capitalize">
                            {s.replace('_', ' ')}
                          </option>
                        ))}
                      </select>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          {orders.last_page > 1 && (
            <div className="flex items-center justify-between border-t border-white/[0.06] px-6 py-4">
              <p className="text-sm text-slate-500">
                Page {orders.current_page} of {orders.last_page} •{' '}
                {orders.total} results
              </p>
              <div className="flex items-center gap-1">
                {orders.links.map((link, i) => {
                  if (i === 0) {
                    return (
                      <Link
                        key="prev"
                        href={link.url || '#'}
                        className={`rounded-lg p-2 text-sm transition-colors ${link.url ? 'text-slate-400 hover:bg-white/[0.06] hover:text-white' : 'cursor-not-allowed text-slate-700'}`}
                        preserveState
                      >
                        <ChevronLeft className="h-4 w-4" />
                      </Link>
                    );
                  }
                  if (i === orders.links.length - 1) {
                    return (
                      <Link
                        key="next"
                        href={link.url || '#'}
                        className={`rounded-lg p-2 text-sm transition-colors ${link.url ? 'text-slate-400 hover:bg-white/[0.06] hover:text-white' : 'cursor-not-allowed text-slate-700'}`}
                        preserveState
                      >
                        <ChevronRight className="h-4 w-4" />
                      </Link>
                    );
                  }
                  return (
                    <Link
                      key={link.label}
                      href={link.url || '#'}
                      className={`rounded-lg px-3 py-1.5 text-sm transition-colors ${link.active
                          ? 'bg-orange-500/20 font-medium text-orange-400'
                          : 'text-slate-400 hover:bg-white/[0.06] hover:text-white'
                        }`}
                      preserveState
                      dangerouslySetInnerHTML={{ __html: link.label }}
                    />
                  );
                })}
              </div>
            </div>
          )}
        </div>
      </div>
    </AdminLayout>
  );
}
