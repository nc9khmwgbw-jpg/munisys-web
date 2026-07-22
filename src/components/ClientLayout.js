"use client";

import { usePathname } from 'next/navigation';
import EditableNavigation from './EditableNavigation';
import EditableFooter from './EditableFooter';
import AdminToolbar from './AdminToolbar';
import CustomCursor from './CustomCursor';

export default function ClientLayout({ children }) {
  const pathname = usePathname();
  const isAdmin = pathname.startsWith('/admin');

  return (
    <>
      <CustomCursor />
      {!isAdmin && <EditableNavigation />}
      {children}
      {!isAdmin && <EditableFooter />}
      {!isAdmin && <AdminToolbar />}
    </>
  );
}