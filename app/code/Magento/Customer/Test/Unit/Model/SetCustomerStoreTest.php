<?php
/************************************************************************
 *
 * Copyright 2023 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Adobe.
 * ************************************************************************
 */
declare(strict_types=1);

namespace Magento\Customer\Test\Unit\Model;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\SetCustomerStore;
use Magento\Store\Api\Data\WebsiteInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SetCustomerStoreTest extends TestCase
{
    /**
     * @var SetCustomerStore
     */
    private $model;

    /**
     * @var MockObject|StoreManagerInterface
     */
    private $storeManagerMock;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $this->model = new SetCustomerStore(
            $this->storeManagerMock
        );
    }

    /**
     * Test for setting up the customer's current store.
     *
     * @param $requestData
     * @dataProvider requestParamsDataProvider
     */
    public function testSetStore($requestData)
    {
        $storeId = $requestData[CustomerInterface::STORE_ID] ?? null;
        $websiteId = $requestData[CustomerInterface::WEBSITE_ID] ?? null;
        if (!$storeId && $websiteId) {
            $storeId = 200;
            $websiteMock = $this->getMockBuilder(WebsiteInterface::class)
                ->disableOriginalConstructor()
                ->addMethods(['getStoreIds'])
                ->getMockForAbstractClass();
            $websiteMock->expects($this->once())
                ->method('getStoreIds')
                ->willReturn([$storeId]);
            $this->storeManagerMock->expects($this->once())
                ->method('getWebsite')
                ->with($websiteId)
                ->willReturn($websiteMock);
        }
        $this->storeManagerMock->expects($this->once())->method('setCurrentStore')->with($storeId);

        $this->model->setStore($requestData);
    }

    /**
     * Provides a customer data array to set the customer's current store.
     *
     * @return array
     */
    public function requestParamsDataProvider(): array
    {
        return [
            [[]],
            [null],
            [['key1' => 4, 'key2' => 'anonymous']],
            [[CustomerInterface::STORE_ID => 5]],
            [[CustomerInterface::STORE_ID => 5, CustomerInterface::WEBSITE_ID => 2]],
            [[CustomerInterface::WEBSITE_ID => 7]]
        ];
    }
}
