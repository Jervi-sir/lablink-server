import { Head, Link, router } from '@inertiajs/react';
import {
  Search,
  Building2,
  CheckCircle2,
  XCircle,
  Eye,
  TrendingUp,
  MapPin,
  Package,
  Users,
  ChevronLeft,
  ChevronRight,
  Star,
} from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '@/layouts/admin/admin-layout';

type Business = {
  id: number;
  name: string;
  nif: string;
  logo: string | null;
  address: string | null;
  category: string | null;
  labCategory: string | null;
  wilaya: string | null;
  isFeatured: boolean;
  productCount: number;
  followerCount: number;
  ownerEmail: string | null;
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
  businesses: PaginatedData<Business>;
  filters: {
    search: string;
  };
};

export default function BusinessesIndex({ businesses, filters }: Props) {
  const [search, setSearch] = useState(filters.search);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get('/admin/businesses', { search }, { preserveState: true });
  };

  const handleToggleFeatured = (businessId: number) => {
    router.patch(`/admin/businesses/${businessId}/toggle-featured`, {}, { preserveState: true });
  };

  return (
    <AdminLayout>
      <Head title="Manage Businesses" />

      <div className="space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight text-white">
              Businesses
            </h1>
            <p className="mt-1 text-sm text-slate-400">
              Manage business profiles and laboratories
            </p>
          </div>
          <div className="flex items-center gap-2 rounded-xl border border-white/[0.06] bg-slate-900/60 px-4 py-2 backdrop-blur-xl">
            <Building2 className="h-4 w-4 text-violet-400" />
            <span className="text-sm font-semibold text-white">
              {businesses.total}
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
              placeholder="Search by name, NIF, or address..."
              className="h-10 w-full rounded-xl border border-white/[0.06] bg-slate-900/60 pl-10 pr-4 text-sm text-white placeholder-slate-500 outline-none backdrop-blur-xl transition-colors focus:border-violet-500/40 focus:ring-1 focus:ring-violet-500/20"
            />
          </form>
        </div>

        {/* Table */}
        <div className="overflow-hidden rounded-2xl border border-white/[0.06] bg-slate-900/60 backdrop-blur-xl">
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead>
                <tr className="border-b border-white/[0.06]">
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Business
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Location
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Stats
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Status
                  </th>
                  <th className="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider text-slate-500">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/[0.04]">
                {businesses.data.map((biz) => (
                  <tr
                    key={biz.id}
                    className="group transition-colors hover:bg-white/[0.02]"
                  >
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        {biz.logo ? (
                          <img
                            src={biz.logo}
                            alt={biz.name}
                            className="h-10 w-10 min-w-10 rounded-xl object-cover"
                          />
                        ) : (
                          <div className="flex h-10 w-10 min-w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-600/20 to-purple-600/20 text-sm font-semibold text-violet-400">
                            {biz.name.charAt(0).toUpperCase()}
                          </div>
                        )}
                        <div className="w-48 xl:w-64">
                          <p className="truncate text-sm font-medium text-slate-200" title={biz.name}>
                            {biz.name}
                          </p>
                          <div className="flex items-center gap-2">
                            <p className="truncate text-xs text-slate-500" title={biz.ownerEmail || ''}>
                              {biz.category || biz.labCategory}
                            </p>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-1.5 text-sm text-slate-300">
                        <MapPin className="h-4 w-4 text-slate-500" />
                        <span className="truncate max-w-[120px]" title={biz.wilaya || 'N/A'}>{biz.wilaya || 'N/A'}</span>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-4 text-xs font-medium text-slate-400">
                        <div className="flex items-center gap-1.5" title="Products">
                          <Package className="h-3.5 w-3.5" />
                          {biz.productCount}
                        </div>
                        <div className="flex items-center gap-1.5" title="Followers">
                          <Users className="h-3.5 w-3.5" />
                          {biz.followerCount}
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      {biz.isFeatured ? (
                        <span className="inline-flex items-center gap-1 rounded-full bg-amber-500/15 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-amber-400 ring-1 ring-inset ring-amber-500/20">
                          <Star className="h-3 w-3 fill-current" />
                          Featured
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                          Standard
                        </span>
                      )}
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center justify-end gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                        <button
                          onClick={() => handleToggleFeatured(biz.id)}
                          className={`rounded-lg p-2 transition-colors hover:bg-white/[0.06] ${biz.isFeatured ? 'text-amber-400 hover:text-amber-300' : 'text-slate-500 hover:text-amber-400'}`}
                          title={biz.isFeatured ? "Remove Featured" : "Mark as Featured"}
                        >
                          <Star className={`h-4 w-4 ${biz.isFeatured ? 'fill-current' : ''}`} />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          {businesses.last_page > 1 && (
            <div className="flex items-center justify-between border-t border-white/[0.06] px-6 py-4">
              <p className="text-sm text-slate-500">
                Page {businesses.current_page} of {businesses.last_page} •{' '}
                {businesses.total} results
              </p>
              <div className="flex items-center gap-1">
                {businesses.links.map((link, i) => {
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
                  if (i === businesses.links.length - 1) {
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
                          ? 'bg-violet-500/20 font-medium text-violet-400'
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
