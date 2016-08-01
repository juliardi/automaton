<?php

namespace automaton;

/**
 *
 */
class HostGenerator extends Base
{
    public function run()
    {
        $append_text = $this->host_ip."\t".$this->host_name;

        if ($this->appendFile($this->host_file, $append_text)) {
            echo "Hostname created successfully\n";

            return true;
        } else {
            echo "Failed to create Hostname\n";

            return false;
        }
    }
}
