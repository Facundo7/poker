<?php

namespace App\Repositories;

interface PlayerRepositoryInterface
{
    /**
     * Get's a player by it's ID
     *
     * @param int
     */
    public function find($id);

    /**
     * Get's all players.
     *
     * @return mixed
     */
    public function all();

    /**
     * Deletes a player.
     *
     * @param int
     */
    public function delete($id);

    /**
     * Creates a player.
     *
     * @param array
     */
    public function save($data);

    /**
     * Updates a player.
     *
     * @param int
     * @param array
     */
    public function update($id, array $data);
}
