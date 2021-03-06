<?php

namespace Model;


use FastD\Model\Model;

class DiscussionsModel extends Model
{
    const TABLE = 'discussions';
    const LIMIT = '15';

    public function select($page = 1, $limit = DiscussionsModel::LIMIT)
    {
        $offset = ($page - 1) * $limit;

        $data = $this->db->select(static::TABLE, '*', [
            'AND' => [
                'reply_id' => 0,
            ],
            'LIMIT' => [$offset, $limit],
        ]);

        return [
            'data' => $data,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $this->db->count(static::TABLE, ['AND' => [
                'reply_id' => 0,
            ]]),
        ];
    }

    public function findTargetDiscussions($id, $page = 1, $limit = DiscussionsModel::LIMIT)
    {
        $offset = ($page - 1) * $limit;

        $data = $this->db->select(static::TABLE, '*', [
            'target_id' => $id,
            'LIMIT' => [$offset, $limit],
        ]);

        return [
            'data' => $data,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $this->db->count(static::TABLE, ['target_id' => $id])
        ];
    }

    public function findUserDiscussions($id, $page = 1, $limit = DiscussionsModel::LIMIT)
    {
        $offset = ($page - 1) * $limit;

        $data = $this->db->select(static::TABLE, '*', [
            'user_id' => $id,
            'LIMIT' => [$offset, $limit],
        ]);

        return [
            'data' => $data,
            'limit' => $limit,
            'offset' => $offset,
            'total' => $this->db->count(static::TABLE, ['user_id' => $id])
        ];
    }

    public function findReplyDiscussions($id, $page = 1, $limit = DiscussionsModel::LIMIT)
    {
        $offset = ($page - 1) * $limit;

        $data = $this->db->select(static::TABLE, '*', [
            'reply_id' => $id,
            'LIMIT' => [$offset, $limit],
        ]);

        return [
            'data' => $data,
            'limit' => $limit,
            'offset' => $offset,
            'total' => $this->db->count(static::TABLE, [
                'reply_id' => $id
            ])
        ];
    }

    public function find($id)
    {
        return $this->db->get(static::TABLE, '*', [
            'id' => $id,
        ]);
    }

    public function patch($id, array $data)
    {
        $affected = $this->db->update(static::TABLE, $data, [
            'OR' => [
                'id' => $id,
            ],
        ]);

        return $this->find($id);
    }

    public function create(array $data)
    {
        $this->db->insert(static::TABLE, $data);

        return $this->find($this->db->id());
    }

    public function deleteUser($id)
    {
        return $this->db->delete(static::TABLE, [
            'id' => $id,
        ]);
    }
}