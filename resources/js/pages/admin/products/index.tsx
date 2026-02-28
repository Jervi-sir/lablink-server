import { Head, Link, router } from '@inertiajs/react';
import {
  Search,
  Package,
  TrendingUp,
  Store,
  CheckCircle2,
  XCircle,
  ChevronLeft,
  ChevronRight,
  Tag,
} from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '@/layouts/admin/admin-layout';

type Product = {
  id: number;
  name: string;
  sku: string | null;
  price: number;
  stock: number;
  isAvailable: boolean;
  isTrending: boolean;
  category: string | null;
  business: string | null;
  image: string | null;
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
  products: PaginatedData<Product>;
  filters: {
    search: string;
    available: string;
  };
};

const availabilityFilters = [
  { label: 'All', value: '' },
  { label: 'Available', value: '1' },
  { label: 'Unavailable', value: '0' },
];

export default function ProductsIndex({ products, filters }: Props) {
  const [search, setSearch] = useState(filters.search);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get('/admin/products', { search, available: filters.available }, { preserveState: true });
  };

  const handleAvailabilityFilter = (available: string) => {
    router.get('/admin/products', { search: filters.search, available }, { preserveState: true });
  };

  const handleToggleTrending = (productId: number) => {
    router.patch(`/admin/products/${productId}/toggle-trending`, {}, { preserveState: true });
  };

  const handleToggleAvailability = (productId: number) => {
    router.patch(`/admin/products/${productId}/toggle-availability`, {}, { preserveState: true });
  };

  return (
    <AdminLayout>
      <Head title="Manage Products" />

      <div className="space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight text-white">
              Products
            </h1>
            <p className="mt-1 text-sm text-slate-400">
              Manage items listed across all businesses
            </p>
          </div>
          <div className="flex items-center gap-2 rounded-xl border border-white/[0.06] bg-slate-900/60 px-4 py-2 backdrop-blur-xl">
            <Package className="h-4 w-4 text-emerald-400" />
            <span className="text-sm font-semibold text-white">
              {products.total}
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
              placeholder="Search by name, SKU..."
              className="h-10 w-full rounded-xl border border-white/[0.06] bg-slate-900/60 pl-10 pr-4 text-sm text-white placeholder-slate-500 outline-none backdrop-blur-xl transition-colors focus:border-emerald-500/40 focus:ring-1 focus:ring-emerald-500/20"
            />
          </form>
          <div className="flex items-center gap-1 rounded-xl border border-white/[0.06] bg-slate-900/60 p-1 backdrop-blur-xl">
            {availabilityFilters.map((af) => (
              <button
                key={af.value}
                onClick={() => handleAvailabilityFilter(af.value)}
                className={`rounded-lg px-3 py-1.5 text-xs font-medium transition-all ${filters.available === af.value
                    ? 'bg-white/[0.1] text-white shadow-sm'
                    : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200'
                  }`}
              >
                {af.label}
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
                    Product
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Pricing / Stock
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
                {products.data.map((product) => (
                  <tr
                    key={product.id}
                    className="group transition-colors hover:bg-white/[0.02]"
                  >
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        {product.image ? (
                          <img
                            src={product.image}
                            alt={product.name}
                            className="h-10 w-10 min-w-10 rounded-xl object-cover"
                          />
                        ) : (
                          <div className="flex h-10 w-10 min-w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-600/20 to-teal-600/20 text-emerald-400">
                            <Package className="h-5 w-5" />
                          </div>
                        )}
                        <div className="w-48 xl:w-64">
                          <p className="truncate text-sm font-medium text-slate-200" title={product.name}>
                            {product.name}
                          </p>
                          <div className="mt-0.5 flex items-center gap-2 text-xs text-slate-500">
                            <span className="flex items-center gap-1 truncate" title={product.business || 'N/A'}>
                              <Store className="h-3 w-3" />
                              {product.business || 'N/A'}
                            </span>
                            <span className="flex items-center gap-1">
                              <Tag className="h-3 w-3" />
                              {product.category || 'N/A'}
                            </span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex flex-col gap-1 text-sm">
                        <span className="font-semibold text-emerald-400">
                          {product.price.toLocaleString()} DZD
                        </span>
                        <span className={`text-xs font-medium ${product.stock > 0 ? 'text-slate-400' : 'text-rose-400'}`}>
                          {product.stock} in stock
                        </span>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex flex-col gap-2">
                        {product.isAvailable ? (
                          <span className="inline-flex w-fit items-center gap-1 rounded-full bg-emerald-500/15 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                            Available
                          </span>
                        ) : (
                          <span className="inline-flex w-fit items-center gap-1 rounded-full bg-rose-500/15 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-rose-400 ring-1 ring-inset ring-rose-500/20">
                            Unavailable
                          </span>
                        )}
                        {product.isTrending && (
                          <span className="inline-flex w-fit items-center gap-1 rounded-full bg-amber-500/15 px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide text-amber-400 ring-1 ring-inset ring-amber-500/20">
                            <TrendingUp className="h-3 w-3" />
                            Trending
                          </span>
                        )}
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex flex-col items-end gap-2 opacity-0 transition-opacity group-hover:opacity-100">
                        <button
                          onClick={() => handleToggleAvailability(product.id)}
                          className={`w-full max-w-[140px] rounded-lg border px-3 py-1.5 text-xs font-medium transition-all ${product.isAvailable
                              ? 'border-white/[0.06] text-slate-400 hover:bg-rose-500/10 hover:text-rose-400 hover:border-rose-500/30'
                              : 'border-emerald-500/30 bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20'
                            }`}
                        >
                          {product.isAvailable ? 'Mark Unavailable' : 'Mark Available'}
                        </button>
                        <button
                          onClick={() => handleToggleTrending(product.id)}
                          className={`w-full max-w-[140px] rounded-lg border px-3 py-1.5 text-xs font-medium transition-all ${product.isTrending
                              ? 'border-white/[0.06] text-slate-400 hover:bg-slate-800 hover:text-white'
                              : 'border-amber-500/30 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20'
                            }`}
                        >
                          {product.isTrending ? 'Remove Trending' : 'Set Trending'}
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          {products.last_page > 1 && (
            <div className="flex items-center justify-between border-t border-white/[0.06] px-6 py-4">
              <p className="text-sm text-slate-500">
                Page {products.current_page} of {products.last_page} •{' '}
                {products.total} results
              </p>
              <div className="flex items-center gap-1">
                {products.links.map((link, i) => {
                  if (i === 0) {
                    /* prev button handled similarly */
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
                  if (i === products.links.length - 1) {
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
                          ? 'bg-emerald-500/20 font-medium text-emerald-400'
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
