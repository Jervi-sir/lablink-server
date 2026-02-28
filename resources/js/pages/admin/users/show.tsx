import { Head, Link, router } from '@inertiajs/react';
import {
  ArrowLeft,
  Mail,
  Phone,
  Shield,
  CheckCircle2,
  XCircle,
  Calendar,
  ShoppingCart,
  Building2,
  GraduationCap,
  ShieldCheck,
  Trash2,
} from 'lucide-react';
import AdminLayout from '@/layouts/admin/admin-layout';

type Props = {
  user: {
    id: number;
    email: string;
    phoneNumber: string | null;
    avatar: string | null;
    role: string;
    isVerified: boolean;
    createdAt: string;
    updatedAt: string;
    studentProfile: {
      firstName: string;
      lastName: string;
    } | null;
    businessProfile: {
      name: string;
      nif: string;
    } | null;
    orderCount: number;
  };
};

const roleColors: Record<string, string> = {
  admin: 'from-rose-600 to-rose-500',
  student: 'from-sky-600 to-sky-500',
  business: 'from-violet-600 to-violet-500',
};

export default function UserShow({ user }: Props) {
  const handleToggleVerification = () => {
    router.patch(`/admin/users/${user.id}/toggle-verification`);
  };

  const handleDelete = () => {
    if (confirm('Are you sure you want to delete this user?')) {
      router.delete(`/admin/users/${user.id}`);
    }
  };

  return (
    <AdminLayout>
      <Head title={`User: ${user.email}`} />

      <div className="space-y-6">
        {/* Back link */}
        <Link
          href="/admin/users"
          className="inline-flex items-center gap-2 text-sm text-slate-400 transition-colors hover:text-white"
        >
          <ArrowLeft className="h-4 w-4" />
          Back to Users
        </Link>

        {/* User Header Card */}
        <div className="relative overflow-hidden rounded-2xl border border-white/[0.06] bg-slate-900/60 p-8 backdrop-blur-xl">
          <div className="flex items-start gap-6">
            <div
              className={`flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br ${roleColors[user.role] || 'from-slate-600 to-slate-500'} text-3xl font-bold text-white shadow-xl`}
            >
              {user.email.charAt(0).toUpperCase()}
            </div>
            <div className="flex-1">
              <div className="flex items-center gap-3">
                <h1 className="text-2xl font-bold text-white">
                  {user.studentProfile
                    ? `${user.studentProfile.firstName} ${user.studentProfile.lastName}`
                    : user.businessProfile
                      ? user.businessProfile.name
                      : user.email}
                </h1>
                {user.isVerified ? (
                  <span className="inline-flex items-center gap-1 rounded-full bg-emerald-500/15 px-2.5 py-0.5 text-xs font-semibold text-emerald-400 ring-1 ring-inset ring-emerald-500/20">
                    <CheckCircle2 className="h-3.5 w-3.5" />
                    Verified
                  </span>
                ) : (
                  <span className="inline-flex items-center gap-1 rounded-full bg-amber-500/15 px-2.5 py-0.5 text-xs font-semibold text-amber-400 ring-1 ring-inset ring-amber-500/20">
                    <XCircle className="h-3.5 w-3.5" />
                    Unverified
                  </span>
                )}
              </div>
              <div className="mt-3 flex flex-wrap items-center gap-4 text-sm text-slate-400">
                <span className="flex items-center gap-1.5">
                  <Mail className="h-4 w-4 text-slate-500" />
                  {user.email}
                </span>
                {user.phoneNumber && (
                  <span className="flex items-center gap-1.5">
                    <Phone className="h-4 w-4 text-slate-500" />
                    {user.phoneNumber}
                  </span>
                )}
                <span className="flex items-center gap-1.5">
                  <Shield className="h-4 w-4 text-slate-500" />
                  <span className="capitalize">{user.role}</span>
                </span>
                <span className="flex items-center gap-1.5">
                  <Calendar className="h-4 w-4 text-slate-500" />
                  Joined {user.createdAt}
                </span>
              </div>
            </div>
            <div className="flex items-center gap-2">
              <button
                onClick={handleToggleVerification}
                className="flex items-center gap-2 rounded-xl border border-white/[0.06] bg-white/[0.04] px-4 py-2.5 text-sm font-medium text-slate-300 transition-all hover:border-emerald-500/30 hover:bg-emerald-500/10 hover:text-emerald-400"
              >
                <ShieldCheck className="h-4 w-4" />
                {user.isVerified ? 'Unverify' : 'Verify'}
              </button>
              <button
                onClick={handleDelete}
                className="flex items-center gap-2 rounded-xl border border-white/[0.06] bg-white/[0.04] px-4 py-2.5 text-sm font-medium text-slate-300 transition-all hover:border-red-500/30 hover:bg-red-500/10 hover:text-red-400"
              >
                <Trash2 className="h-4 w-4" />
                Delete
              </button>
            </div>
          </div>
          <div className="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-gradient-to-br from-blue-600/5 to-cyan-600/5 blur-3xl" />
        </div>

        {/* Info Grid */}
        <div className="grid grid-cols-1 gap-6 md:grid-cols-3">
          {/* Orders stat */}
          <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl">
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/10">
                <ShoppingCart className="h-5 w-5 text-orange-400" />
              </div>
              <div>
                <p className="text-2xl font-bold text-white">
                  {user.orderCount}
                </p>
                <p className="text-xs text-slate-500">
                  Total Orders
                </p>
              </div>
            </div>
          </div>

          {/* Profile info */}
          {user.studentProfile && (
            <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl">
              <div className="flex items-center gap-3">
                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/10">
                  <GraduationCap className="h-5 w-5 text-sky-400" />
                </div>
                <div>
                  <p className="text-sm font-medium text-white">
                    {user.studentProfile.firstName} {user.studentProfile.lastName}
                  </p>
                  <p className="text-xs text-slate-500">
                    Student Profile
                  </p>
                </div>
              </div>
            </div>
          )}

          {user.businessProfile && (
            <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl">
              <div className="flex items-center gap-3">
                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10">
                  <Building2 className="h-5 w-5 text-violet-400" />
                </div>
                <div>
                  <p className="text-sm font-medium text-white">
                    {user.businessProfile.name}
                  </p>
                  <p className="text-xs text-slate-500">
                    NIF: {user.businessProfile.nif}
                  </p>
                </div>
              </div>
            </div>
          )}

          {/* Updated at */}
          <div className="rounded-2xl border border-white/[0.06] bg-slate-900/60 p-6 backdrop-blur-xl">
            <div className="flex items-center gap-3">
              <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-500/10">
                <Calendar className="h-5 w-5 text-teal-400" />
              </div>
              <div>
                <p className="text-sm font-medium text-white">
                  {user.updatedAt}
                </p>
                <p className="text-xs text-slate-500">
                  Last Updated
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  );
}
