<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\MessageQueue;

/**
 * Interface for mapping between original and merged messages.
 */
interface MergedMessageInterface
{
    /**
     * Get merged message instance.
     *
     * @return object
     */
    public function getMergedMessage();

    /**
     * Get original messages ids connected with the merged message.
     *
     * @return array
     */
    public function getOriginalMessagesIds();
}
