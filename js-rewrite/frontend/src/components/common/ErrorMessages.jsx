import React from 'react';

// Maps to app/Views/components/errors.php
export default function ErrorMessages({ errors }) {
  if (!errors || (Array.isArray(errors) && errors.length === 0)) return null;

  return (
    <div className="alert alert-danger">
      <ul>
        {Array.isArray(errors) ? (
          errors.map((err, index) => <li key={index}>{err}</li>)
        ) : (
          <li>{errors}</li>
        )}
      </ul>
    </div>
  );
}
