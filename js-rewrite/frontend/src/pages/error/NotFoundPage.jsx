import { Link } from 'react-router-dom';

export default function NotFoundPage() {
  return (
    <>
      <style>
        {`
          .error-body {
              height: 100vh;
              background: #fafafa;
              font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
              color: #777;
              font-weight: 300;
              margin: 0;
              padding: 0;
              display: flex;
              align-items: flex-start;
              justify-content: center;
          }
          .error-wrap {
              max-width: 1024px;
              width: 100%;
              margin: 5rem auto;
              padding: 2rem;
              background: #fff;
              text-align: center;
              border: 1px solid #efefef;
              border-radius: 0.5rem;
              position: relative;
          }
          .error-h1 {
              font-weight: lighter;
              letter-spacing: normal;
              font-size: 3rem;
              margin-top: 0;
              margin-bottom: 0;
              color: #222;
          }
          .error-p {
              margin-top: 1.5rem;
          }
          .error-link:active,
          .error-link:link,
          .error-link:visited {
              color: #dd4814;
              text-decoration: none;
              font-weight: bold;
              margin-top: 1rem;
              display: inline-block;
          }
        `}
      </style>
      <div className="error-body">
        <div className="error-wrap">
          <h1 className="error-h1">404</h1>
          <p className="error-p">
            Sorry! Cannot seem to find the page you were looking for.
          </p>
          <p>
            <Link to="/" className="error-link">Kembali ke Beranda</Link>
          </p>
        </div>
      </div>
    </>
  );
}
