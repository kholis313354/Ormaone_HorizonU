import React from 'react';

// Maps to app/Views/components/flash.php
export default function FlashMessage({ success }) {
  if (!success) return null;

  return (
    <div className="alert alert-success">
      {success}
    </div>
  );
}
