<?php

/**
 *
 */
class wController {

    /**
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before rendering)
     *
     * @property $URL the URL the browser should be redirected to. Send the full URL
     * @see Controller::doRender()
     */
    public function redirect($URL) {
        header("Location: $URL");
        exit();
    }

	/**
     *  Render a template and print the logged in user to PQP
     */
    public function view($view, $data) {
        require($this->view);
    }
}

?>