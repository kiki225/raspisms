<?php

/*
 * This file is part of RaspiSMS.
 *
 * (c) Pierre-Lin Bonnemaison <plebwebsas@gmail.com>
 *
 * This source file is subject to the GPL-3.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace controllers\internals;

    class Command extends StandardController
    {
        protected $model = false;

        /**
         * Get the model for the Controller
         * @return \descartes\Model
         */
        protected function get_model () : \descartes\Model
        {
            $this->model = $this->model ?? new \models\Command($this->$bdd);
            return $this->model;
        }

        
        /**
         * Create a new command
         * @param int $id_user : User id
         * @param string $name : Command name
         * @param string $script : Script file
         * @param bool $admin : Is command admin only
         * @return mixed bool|int : False if cannot create command, id of the new command else
         */
        public function create(int $id_user, string $name, string $script, bool $admin)
        {
            $command = [
                'id_user' => $id_user,
                'name' => $name,
                'script' => $script,
                'admin' => $admin,
            ];

            $result = $this->model_command->insert($command);
            if (!$result)
            {
                return false;
            }

            $this->internal_event->create($id_user, 'COMMAND_ADD', 'Ajout commande : ' . $name . ' => ' . $script);
            
            return $result;
        }
        
        
        /**
         * Update a command
         * @param int $id_user : User id
         * @param int $id : Command id
         * @param string $name : Command name
         * @param string $script : Script file
         * @param bool $admin : Is command admin only
         * @return mixed bool|int : False if cannot create command, id of the new command else
         */
        public function update_for_user(int $id_user, int $id, string $name, string $script, bool $admin)
        {
            $command = [
                'name' => $name,
                'script' => $script,
                'admin' => $admin,
            ];

            return $this->model_command->update_for_user($id_user, $id, $command);
        }
    }
