<?php
if (!isset($channel)) {
    $channel = [];
}
if (!isset($channel['title'])) {
    $channel['title'] = $title_for_layout;
}

echo $this->Rss->document(
    $this->Rss->channel(
        [], $channel, $this->fetch('content')
    )
);
?>
