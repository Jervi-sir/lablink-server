import { Head, Link, router } from '@inertiajs/react';
import {
  Search,
  Users,
  Shield,
  CheckCircle2,
  XCircle,
  Eye,
  Trash2,
  ShieldCheck,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import { useState } from 'react';
import AdminLayout from '@/layouts/admin/admin-layout';

type User = {
  id: number;
  email: string;
  phoneNumber: string | null;
  avatar: string | null;
  role: string;
  isVerified: boolean;
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
  users: PaginatedData<User>;
  filters: {
    search: string;
    role: string;
  };
};

const roleColors: Record<string, string> = {
  admin: 'bg-rose-500/15 text-rose-400 ring-rose-500/20',
  student: 'bg-sky-500/15 text-sky-400 ring-sky-500/20',
  business: 'bg-violet-500/15 text-violet-400 ring-violet-500/20',
};

const roleFilters = [
  { label: 'All', value: '' },
  { label: 'Admin', value: 'admin' },
  { label: 'Student', value: 'student' },
  { label: 'Business', value: 'business' },
];

export default function UsersIndex({ users, filters }: Props) {
  const [search, setSearch] = useState(filters.search);

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    router.get('/admin/users', { search, role: filters.role }, { preserveState: true });
  };

  const handleRoleFilter = (role: string) => {
    router.get('/admin/users', { search: filters.search, role }, { preserveState: true });
  };

  const handleToggleVerification = (userId: number) => {
    router.patch(`/admin/users/${userId}/toggle-verification`, {}, { preserveState: true });
  };

  const handleDelete = (userId: number) => {
    if (confirm('Are you sure you want to delete this user?')) {
      router.delete(`/admin/users/${userId}`, { preserveState: true });
    }
  };

  return (
    <AdminLayout>
      <Head title="Manage Users" />

      <div className="space-y-6">
        {/* Header */}
        <div className="flex items-center justify-between">
          <div>
            <h1 className="text-3xl font-bold tracking-tight text-white">
              Users
            </h1>
            <p className="mt-1 text-sm text-slate-400">
              Manage all registered users across the platform
            </p>
          </div>
          <div className="flex items-center gap-2 rounded-xl border border-white/[0.06] bg-slate-900/60 px-4 py-2 backdrop-blur-xl">
            <Users className="h-4 w-4 text-blue-400" />
            <span className="text-sm font-semibold text-white">
              {users.total}
            </span>
            <span className="text-sm text-slate-500">total</span>
          </div>
        </div>

        {/* Filters Bar */}
        <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <form onSubmit={handleSearch} className="relative max-w-sm flex-1">
            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
            <input
              type="text"
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              placeholder="Search by email or phone..."
              className="h-10 w-full rounded-xl border border-white/[0.06] bg-slate-900/60 pl-10 pr-4 text-sm text-white placeholder-slate-500 outline-none backdrop-blur-xl transition-colors focus:border-blue-500/40 focus:ring-1 focus:ring-blue-500/20"
            />
          </form>
          <div className="flex items-center gap-1 rounded-xl border border-white/[0.06] bg-slate-900/60 p-1 backdrop-blur-xl">
            {roleFilters.map((rf) => (
              <button
                key={rf.value}
                onClick={() => handleRoleFilter(rf.value)}
                className={`rounded-lg px-3 py-1.5 text-xs font-medium transition-all ${filters.role === rf.value
                    ? 'bg-white/[0.1] text-white shadow-sm'
                    : 'text-slate-400 hover:bg-white/[0.04] hover:text-slate-200'
                  }`}
              >
                {rf.label}
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
                    User
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Role
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Status
                  </th>
                  <th className="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                    Joined
                  </th>
                  <th className="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider text-slate-500">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody className="divide-y divide-white/[0.04]">
                {users.data.map((user) => (
                  <tr
                    key={user.id}
                    className="group transition-colors hover:bg-white/[0.02]"
                  >
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-slate-700 to-slate-600 text-sm font-semibold text-white">
                          {user.email.charAt(0).toUpperCase()}
                        </div>
                        <div>
                          <p className="text-sm font-medium text-slate-200">
                            {user.email}
                          </p>
                          {user.phoneNumber && (
                            <p className="text-xs text-slate-500">{user.phoneNumber}</p>
                          )}
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4">
                      <span
                        className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase tracking-wide ring-1 ring-inset ${roleColors[user.role] || 'bg-slate-500/15 text-slate-400 ring-slate-500/20'}`}
                      >
                        <Shield className="h-3 w-3" />
                        {user.role}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      {user.isVerified ? (
                        <span className="inline-flex items-center gap-1 text-emerald-400">
                          <CheckCircle2 className="h-4 w-4" />
                          <span className="text-xs font-medium">Verified</span>
                        </span>
                      ) : (
                        <span className="inline-flex items-center gap-1 text-amber-400">
                          <XCircle className="h-4 w-4" />
                          <span className="text-xs font-medium">Unverified</span>
                        </span>
                      )}
                    </td>
                    <td className="px-6 py-4">
                      <span className="text-sm text-slate-400">
                        {user.createdAt}
                      </span>
                    </td>
                    <td className="px-6 py-4">
                      <div className="flex items-center justify-end gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                        <Link
                          href={`/admin/users/${user.id}`}
                          className="rounded-lg p-2 text-slate-500 transition-colors hover:bg-white/[0.06] hover:text-blue-400"
                          title="View"
                        >
                          <Eye className="h-4 w-4" />
                        </Link>
                        <button
                          onClick={() => handleToggleVerification(user.id)}
                          className="rounded-lg p-2 text-slate-500 transition-colors hover:bg-white/[0.06] hover:text-emerald-400"
                          title="Toggle verification"
                        >
                          <ShieldCheck className="h-4 w-4" />
                        </button>
                        <button
                          onClick={() => handleDelete(user.id)}
                          className="rounded-lg p-2 text-slate-500 transition-colors hover:bg-white/[0.06] hover:text-red-400"
                          title="Delete"
                        >
                          <Trash2 className="h-4 w-4" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          {users.last_page > 1 && (
            <div className="flex items-center justify-between border-t border-white/[0.06] px-6 py-4">
              <p className="text-sm text-slate-500">
                Page {users.current_page} of {users.last_page} •{' '}
                {users.total} results
              </p>
              <div className="flex items-center gap-1">
                {users.links.map((link, i) => {
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
                  if (i === users.links.length - 1) {
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
                          ? 'bg-blue-500/20 font-medium text-blue-400'
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
