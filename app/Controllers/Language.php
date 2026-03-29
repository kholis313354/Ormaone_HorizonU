<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function index()
    {
        $session = session();
        $locale = $this->request->getLocale();
        $session->remove('lang');
        $session->set('lang', $locale);
        return redirect()->back();
    }

    public function switch($locale)
    {
        $session = session();

        // Remove existing lang session
        $session->remove('lang');

        // Check if the requested locale is supported
        $validLocales = ['id', 'en'];
        if (in_array($locale, $validLocales)) {
            $session->set('lang', $locale);

            // Also set CI4 locale mainly
            service('request')->setLocale($locale);
        }

        return redirect()->back();
    }
}
