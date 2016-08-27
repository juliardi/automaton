<?php

namespace automaton;

class VhostGenerator extends Base
{
    public function run()
    {
        $template = $this->readFile($this->template_dir.'/vhost.conf');
        $vhost_conf = $this->replaceString($template);
        $conf_filename = $this->vhost_conf_dir.DIRECTORY_SEPARATOR.$this->server_name.'.conf';

        if ($this->writeFile($conf_filename, $vhost_conf)) {
            if ($this->includeConfToApache($conf_filename)) {
                echo "Virtual Host created successfully\n";

                return true;
            } else {
                echo "Failed to create Virtual Host\n";

                return false;
            }
        } else {
            echo "Failed to create Virtual Host\n";

            return false;
        }
    }

    public function includeConfToApache($conf_filename)
    {
        $append_text = 'Include "'.$conf_filename.'"';

        return $this->appendFile($this->apache_conf_file, $append_text);
    }

    private function replaceString($templateContent)
    {
        $err_log_name = $this->log_dir.DIRECTORY_SEPARATOR.$this->server_name.'-error.log';
        $custom_log_name = $this->log_dir.DIRECTORY_SEPARATOR.$this->server_name.'-access.log';

        $templateServer = str_replace('{{SERVER_NAME}}', $this->server_name, $templateContent);
        $templateDocRoot = str_replace('{{DOCUMENT_ROOT}}', $this->document_root, $templateServer);
        $templateErrLog = str_replace('{{ERROR_LOG}}', $err_log_name, $templateDocRoot);
        $result = str_replace('{{CUSTOM_LOG}}', $custom_log_name, $templateErrLog);

        return $result;
    }
}
