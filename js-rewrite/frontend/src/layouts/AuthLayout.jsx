import { Outlet } from 'react-router-dom';
import { useEffect } from 'react';

export default function AuthLayout() {
  useEffect(() => {
    document.body.style.backgroundColor = '#f2f7ff';
    return () => { document.body.style.backgroundColor = ''; };
  }, []);

  return (
    <>
      <link rel="stylesheet" href="/dist/ormaone/css/login2.css" />
      <link rel="stylesheet" href="/dist/ormaone/css/style.css" />
      <div className="container-fluid d-flex">
        <Outlet />
      </div>
    </>
  );
}
