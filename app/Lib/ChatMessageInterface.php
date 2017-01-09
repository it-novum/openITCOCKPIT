<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatMessageInterface implements MessageComponentInterface
{
    protected $clients;

    public function __construct($cakeThis)
    {
        $this->clients = new \SplObjectStorage;
        $this->Cake = $cakeThis;
        $this->connectedUsers = [];

    }

    public function eventLoop()
    {
        if (count($this->clients) > 1) {
            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'type'    => 'server_message',
                    'time'    => date('Y-m-d H:i:s'),
                    'user'    => 'Event Loop',
                    'message' => 'Currently there are '.count($this->clients).' users connected.',
                ]));
            }
        }

    }

    public function onOpen(ConnectionInterface $conn)
    {
        $conn->send(json_encode([
            'type'    => 'server_message',
            'time'    => date('Y-m-d H:i:s'),
            'user'    => 'Server',
            'message' => 'Welcome to the Chat. This is a public chat room, your messages will not get logged by the server... <br /> Number of People in public room: '.(count($this->clients) + 1),
        ]));

        $conn->identifier = md5(time().rand().rand().rand());

        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $msg = json_decode($msg);
        //debug($msg);
        if ($msg->type == 'connection') {
            $newUser = $this->newConnection($msg, $from->identifier);
            foreach ($this->clients as $n => $client) {
                if ($from !== $client) {
                    $client->send(json_encode([
                        'type'    => 'server_message',
                        'time'    => date('Y-m-d H:i:s'),
                        'user'    => 'Chat Server',
                        'message' => 'User <b>'.$newUser['full_name'].'</b> has joined the room',
                    ]));
                }
            }

            return;
        }

        if ($msg->type == 'keepAlive') {
            foreach ($this->clients as $n => $client) {
                if ($from == $client) {
                    $client->send(json_encode([
                        'type'    => 'keepAlive',
                        'time'    => date('Y-m-d H:i:s'),
                        'user'    => 'Chat Server',
                        'message' => 'Pong',
                    ]));
                }
            }

            return;
        }

        if (!isset($this->connectedUsers[$msg->user_id])) {
            //User not found in connectedUsers array
            return;
        }
        foreach ($this->clients as $n => $client) {
            //if ($from !== $client) { /* Sorgt dafür das ein Benutzer seine eigenen Nachrichten nicht angezeigt bekommt*/
            $client->send(json_encode([
                'type'    => 'message',
                'time'    => date('Y-m-d H:i:s'),
                'image'   => $this->connectedUsers[$msg->user_id]['image'],
                'user'    => $this->connectedUsers[$msg->user_id]['full_name'],
                'message' => $msg->message,
            ]));
            //}
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $userIdToUnset = 0;
        foreach ($this->connectedUsers as $user_id => $user) {
            if ($user['identifier'] == $conn->identifier) {
                $userIdToUnset = $user_id;
            }
        }

        $this->clients->detach($conn);

        if ($userIdToUnset > 0) {
            foreach ($this->clients as $n => $client) {
                $client->send(json_encode([
                    'type'    => 'server_message',
                    'time'    => date('Y-m-d H:i:s'),
                    'user'    => 'Chat Server',
                    'message' => 'User <b>'.$this->connectedUsers[$userIdToUnset]['full_name'].'</b> has left the room',
                ]));
            }
            unset($this->connectedUsers[$userIdToUnset]);
        }

    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
        $this->clients->detach($conn);
    }

    public function newConnection($message, $identifier)
    {
        //Reset MySQL connection to avoid "MySQL hase von away"
        $this->Cake->User->getDatasource()->reconnect();
        $user = $this->Cake->User->findById($message->user_id);
        if (empty($user)) {
            return;
        }

        if ($user['User']['image'] != null && $user['User']['image'] != ''):
            if (file_exists(WWW_ROOT.'userimages'.DS.$user['User']['image'])):
                $img = '/userimages'.DS.$user['User']['image'];
            else:
                $img = '/img/fallback_user.png';
            endif;
        else:
            $img = '/img/fallback_user.png';
        endif;

        $userData = [
            'full_name'  => $user['User']['full_name'],
            'image'      => $img,
            'email'      => $user['User']['email'],
            'dateformat' => $user['User']['dateformat'],
            'timezone'   => $user['User']['timezone'],
            'identifier' => $identifier,
        ];

        $this->connectedUsers[$user['User']['id']] = $userData;

        return $userData;
    }
}