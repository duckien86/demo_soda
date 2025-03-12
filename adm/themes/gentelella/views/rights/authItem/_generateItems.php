<?php if ($items['controllers'] !== array()): ?>

    <?php foreach ($items['controllers'] as $key => $item): ?>

        <?php if (isset($item['actions']) === TRUE && $item['actions'] !== array()): ?>

            <?php $controllerKey = isset($moduleName) === TRUE ? ucfirst($moduleName) . '.' . $item['name'] : $item['name']; ?>
            <?php $controllerExists = isset($existingItems[$controllerKey . '.*']); ?>

            <tr class="controller-row <?php echo $controllerExists === TRUE ? 'exists' : ''; ?>">
                <td class="checkbox-column"><?php echo $controllerExists === FALSE ? $form->checkBox($model, 'items[' . $controllerKey . '.*]') : ''; ?></td>
                <td class="name-column"><?php echo ucfirst($item['name']) . '.*'; ?></td>
                <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1); ?></td>
            </tr>

            <?php $i = 0;
            foreach ($item['actions'] as $action): ?>

                <?php $actionKey = $controllerKey . '.' . ucfirst($action['name']); ?>
                <?php $actionExists = isset($existingItems[$actionKey]); ?>

                <tr class="action-row<?php echo $actionExists === TRUE ? ' exists' : ''; ?><?php echo ($i++ % 2) === 0 ? ' odd' : ' even'; ?>">
                    <td class="checkbox-column"><?php echo $actionExists === FALSE ? $form->checkBox($model, 'items[' . $actionKey . ']') : ''; ?></td>
                    <td class="name-column"><?php echo $action['name']; ?></td>
                    <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1) . (isset($action['line']) === TRUE ? ':' . $action['line'] : ''); ?></td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    <?php endforeach; ?>

<?php else: ?>


    <tr>
        <th class="no-items-row" colspan="3"><?php echo Rights::t('core', 'No actions found.'); ?></th>
    </tr>
<?php endif; ?>
<?php if ($items['controllers_report'] !== array()): ?>

    <?php foreach ($items['controllers_report'] as $key => $item): ?>

        <?php if (isset($item['actions']) === TRUE && $item['actions'] !== array()): ?>

            <?php $controllerKey = isset($moduleName) === TRUE ? ucfirst($moduleName) . '.' . $item['name'] : $item['name']; ?>
            <?php $controllerExists = isset($existingItems[$controllerKey . '.*']); ?>

            <tr class="controller-row <?php echo $controllerExists === TRUE ? 'exists' : ''; ?>">
                <td class="checkbox-column"><?php echo $controllerExists === FALSE ? $form->checkBox($model, 'items[' . $controllerKey . '.*]') : ''; ?></td>
                <td class="name-column"><?php echo ucfirst($item['name']) . '.*'; ?></td>
                <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1); ?></td>
            </tr>

            <?php $i = 0;
            foreach ($item['actions'] as $action): ?>

                <?php $actionKey = $controllerKey . '.' . ucfirst($action['name']); ?>
                <?php $actionExists = isset($existingItems[$actionKey]); ?>

                <tr class="action-row<?php echo $actionExists === TRUE ? ' exists' : ''; ?><?php echo ($i++ % 2) === 0 ? ' odd' : ' even'; ?>">
                    <td class="checkbox-column"><?php echo $actionExists === FALSE ? $form->checkBox($model, 'items[' . $actionKey . ']') : ''; ?></td>
                    <td class="name-column"><?php echo $action['name']; ?></td>
                    <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1) . (isset($action['line']) === TRUE ? ':' . $action['line'] : ''); ?></td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    <?php endforeach; ?>

<?php else: ?>


    <tr>
        <th class="no-items-row" colspan="3"><?php echo Rights::t('core', 'No actions found.'); ?></th>
    </tr>
<?php endif; ?>
<?php if ($items['controllers_cskh'] !== array()): ?>

    <?php foreach ($items['controllers_cskh'] as $key => $item): ?>

        <?php if (isset($item['actions']) === TRUE && $item['actions'] !== array()): ?>

            <?php $controllerKey = isset($moduleName) === TRUE ? ucfirst($moduleName) . '.' . $item['name'] : $item['name']; ?>
            <?php $controllerExists = isset($existingItems[$controllerKey . '.*']); ?>

            <tr class="controller-row <?php echo $controllerExists === TRUE ? 'exists' : ''; ?>">
                <td class="checkbox-column"><?php echo $controllerExists === FALSE ? $form->checkBox($model, 'items[' . $controllerKey . '.*]') : ''; ?></td>
                <td class="name-column"><?php echo ucfirst($item['name']) . '.*'; ?></td>
                <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1); ?></td>
            </tr>

            <?php $i = 0;
            foreach ($item['actions'] as $action): ?>

                <?php $actionKey = $controllerKey . '.' . ucfirst($action['name']); ?>
                <?php $actionExists = isset($existingItems[$actionKey]); ?>

                <tr class="action-row<?php echo $actionExists === TRUE ? ' exists' : ''; ?><?php echo ($i++ % 2) === 0 ? ' odd' : ' even'; ?>">
                    <td class="checkbox-column"><?php echo $actionExists === FALSE ? $form->checkBox($model, 'items[' . $actionKey . ']') : ''; ?></td>
                    <td class="name-column"><?php echo $action['name']; ?></td>
                    <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1) . (isset($action['line']) === TRUE ? ':' . $action['line'] : ''); ?></td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    <?php endforeach; ?>

<?php else: ?>


    <tr>
        <th class="no-items-row" colspan="3"><?php echo Rights::t('core', 'No actions found.'); ?></th>
    </tr>
<?php endif; ?>

<?php if ($items['controllers_agency'] !== array()): ?>

    <?php foreach ($items['controllers_agency'] as $key => $item): ?>

        <?php if (isset($item['actions']) === TRUE && $item['actions'] !== array()): ?>

            <?php $controllerKey = isset($moduleName) === TRUE ? ucfirst($moduleName) . '.' . $item['name'] : $item['name']; ?>
            <?php $controllerExists = isset($existingItems[$controllerKey . '.*']); ?>

            <tr class="controller-row <?php echo $controllerExists === TRUE ? 'exists' : ''; ?>">
                <td class="checkbox-column"><?php echo $controllerExists === FALSE ? $form->checkBox($model, 'items[' . $controllerKey . '.*]') : ''; ?></td>
                <td class="name-column"><?php echo ucfirst($item['name']) . '.*'; ?></td>
                <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1); ?></td>
            </tr>

            <?php $i = 0;
            foreach ($item['actions'] as $action): ?>

                <?php $actionKey = $controllerKey . '.' . ucfirst($action['name']); ?>
                <?php $actionExists = isset($existingItems[$actionKey]); ?>

                <tr class="action-row<?php echo $actionExists === TRUE ? ' exists' : ''; ?><?php echo ($i++ % 2) === 0 ? ' odd' : ' even'; ?>">
                    <td class="checkbox-column"><?php echo $actionExists === FALSE ? $form->checkBox($model, 'items[' . $actionKey . ']') : ''; ?></td>
                    <td class="name-column"><?php echo $action['name']; ?></td>
                    <td class="path-column"><?php echo substr($item['path'], $basePathLength + 1) . (isset($action['line']) === TRUE ? ':' . $action['line'] : ''); ?></td>
                </tr>

            <?php endforeach; ?>

        <?php endif; ?>

    <?php endforeach; ?>

<?php else: ?>


    <tr>
        <th class="no-items-row" colspan="3"><?php echo Rights::t('core', 'No actions found.'); ?></th>
    </tr>
<?php endif; ?>

<?php if ($items['modules'] !== array()): ?>

    <?php if ($displayModuleHeadingRow === TRUE): ?>

        <tr>
            <th class="module-heading-row" colspan="3"><?php echo Rights::t('core', 'Modules'); ?></th>
        </tr>

    <?php endif; ?>
    <?php

    ?>
    <?php foreach ($items['modules'] as $moduleName => $moduleItems): ?>
        <?php foreach ($moduleItems as $moduleNameControllers => $moduleItemsControllers): ?>
            <?php
            if ($moduleNameControllers == 'controllers') {
                ?>
                <tr>
                    <th class="module-row" colspan="3"><?php echo ucfirst($moduleName) . 'Module'; ?></th>
                </tr>

                <?php $this->renderPartial('_generateItems', array(
                    'model'                   => $model,
                    'form'                    => $form,
                    'items'                   => $moduleItems,
                    'existingItems'           => $existingItems,
                    'moduleName'              => $moduleName,
                    'displayModuleHeadingRow' => FALSE,
                    'basePathLength'          => $basePathLength,
                )); ?>
                <?php
            } else {
                break;
            }
            ?>
        <?php endforeach; ?>


    <?php endforeach; ?>

<?php endif; ?>