<script src="<?= $this->vendorsURL ?>/jQuery-ajaxTransport-XDomainRequest/jquery.xdomainrequest.min.js"></script>
<div class="downloadPage">
  <table>
    <thead>
      <tr>
        <th><?= htmlspecialchars($this->_('Name')) ?></th>
        <th><?= htmlspecialchars($this->_('Description')) ?></th>
        <th><?= htmlspecialchars($this->_('Format')) ?></th>
      </tr>
    </thead>

<?php foreach ($this->tab as $tab): ?>
    <tbody>
      <tr>
        <th><?= htmlspecialchars($tab['name']) ?></th>
        <th><?= htmlspecialchars($tab['description']) ?></th>
        <th colspan=3></th>
      </tr>
<?php   foreach ($tab['chart'] as $j => $chart): ?>
<?php     foreach ($chart['download'] as $i => $dl): ?>
      <tr class="<?= $j % 2 ? 'odd' : 'even' ?>">
<?php       if (!$i): ?>
        <td rowspan="<?= count($chart['download']) ?>"><?= htmlspecialchars($chart['name']) ?></td>
        <td rowspan="<?= count($chart['download']) ?>"><?= htmlspecialchars($chart['description']) ?></td>
<?php       endif ?>
        <td><a href="<?= htmlspecialchars($dl['url']) ?>" data-type="<?= htmlspecialchars($dl['mime']) ?>" data-flow="<?= htmlspecialchars($chart['flow']) ?>" download="<?= htmlspecialchars($chart['flow'].preg_replace('/[^a-z0-9-]+/i','-',"-".$tab['name']."-".$chart['name']).".".strtolower($dl['format'])) ?>"><?= htmlspecialchars($dl['format']) ?></a></td>
      </tr>
<?php     endforeach ?>
<?php   endforeach ?>
    </tbody>
<?php endforeach ?>

  </table>

<?php if (isset($this->note)): ?>
  <div><?= htmlspecialchars($this->note) ?></div>
<?php endif ?>

  <div class="wrapper clearfix"></div>
</div>
