<?php
assert($this instanceof Qiq);
$this->setLayout('layout/base');
?>
<h2>UserPage:Index</h2><br>
Hello: {{h $this->username }}<br>
<a href="logout">logout</a>
