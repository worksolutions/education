<?php

namespace Domain\TestArtifacts;
use Bitrix\Sale\Basket;
use Bitrix\Sale\DeliveryService;
use Bitrix\Sale\DeliveryTable;
use Bitrix\Sale\Order;
use Bitrix\Sale\Services\Company\Restrictions\Delivery;
use Couchbase\Exception;

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class OrderMaker {

    private $userId;
    private $usingProducts = array();

    /**
     * OrderMaker constructor.
     * @param $userId
     * @throws \Exception
     */
    public function __construct($userId) {
        \CModule::IncludeModule("sale");
        $arUser = !!\CUser::GetByID($userId)->Fetch();
        if (!$arUser) {
            throw new \Exception("Пользователь с идентификатором `{$userId}` не существует");
        }
        $this->userId = $userId;
    }

    /**
     * @param array $ids
     * @return $this
     * @throws \Exception
     */
    public function useProducts(array $ids) {
        $dbRes = \CCatalog::GetList(array(), array("=ID" => $ids));
        $actualIds = array();
        while ($arProduct = $dbRes->Fetch()) {
            $actualIds[] = $arProduct['ID'];
        }
        $diff = array_diff($ids, $actualIds);
        if ($diff) {
            $strDiff = implode(", ", $diff);
            throw new \Exception("Товаров с идентификаторами {$strDiff} не существует");
        }
        $this->usingProducts = array_unique(array_merge($this->usingProducts, $ids));
        return $this;
    }

    /**
     * @return Basket
     * @throws Exception
     */
    public function fillBasket() {
        $basket = Basket::create(SITE_ID);
        $basket->clearCollection();
        foreach ($this->usingProducts as $product) {
            $basket->createItem("catalog", $product);
        }
        $result = $basket->save();
        if (!$result->isSuccess()) {
            throw new Exception(implode(", ", $result->getErrorMessages()));
        }
        return $basket;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function create() {
        $this->fillBasket();
        $arFields = array(
            "SITE_ID" => \CSite::GetList()->Fetch()['ID'],
            "PERSON_TYPE_ID" => 1,
            "PAYED" => "N",
            "CANCELED" => "N",
            "STATUS_ID" => \CSaleStatus::GetList()->Fetch()['ID'],
            "PRICE" => 279.32,
            "CURRENCY" => "USD",
            "USER_ID" => IntVal($this->userId),
            "PAY_SYSTEM_ID" => 3,
            "PRICE_DELIVERY" => 11.37,
            "DELIVERY_ID" => 3,
            "DISCOUNT_VALUE" => 1.5,
            "TAX_VALUE" => 0.0,
            "USER_DESCRIPTION" => ""
        );


        $orderId = \CSaleOrder::Add($arFields);
        if (!$orderId) {
            global $APPLICATION;
            throw new \Exception($APPLICATION->GetException()->GetString());
        }
        return $orderId;
    }
}
