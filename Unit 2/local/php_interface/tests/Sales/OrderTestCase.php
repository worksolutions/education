<?php

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class OrderTestCase extends \WS\BUnit\Cases\BaseCase {

    const COUNT_PRODUCTS = 3;

    public function setUp() {
        CModule::IncludeModule("catalog");
        CModule::IncludeModule("sale");
    }

    /**
     * Не является модульным тестом, а интеграционным (или смоук тестом), так как взаимодействия
     * с функционалом напрямую - нет
     *
     * @label integration
     *
     * @test
     */
    public function backAmountAfterOrderCancel() {

        // Подготовка теста
        list($orderId, $userId) = $this->createOrder();

        $this->getAssert()->asTrue($orderId > 0, "Заказ не создан");

        $payResult = CSaleOrder::PayOrder($orderId, "Y", false, true);
        $this->getAssert()->asTrue((bool) $payResult, "Заказа не перешел в состояние \"оплачено\"");

        $arAccount = CSaleUserAccount::GetByUserID($userId, "RUB");
        $this->getAssert()->equal($arAccount['AMOUNT'], 0, "Счет должен быть нулевым");

        // Единое действие
        CSaleOrder::CancelOrder($orderId, "Y");

        // Проверки корректности выполнения

        $arAccount = CSaleUserAccount::GetByUserID($userId, "RUB");
        $this->getAssert()->asFalse($arAccount['AMOUNT'] > 0, "Счет не пополнился после отмены заказа");

        // очистка, если требуется
    }

    /**
     * Не является модульным тестом, а интеграционным (или смоук тестом), так как взаимодействия
     * с функционалом напрямую - нет
     *
     * @label integration
     *
     * @test
     */
    public function sendEmailAfterPayment() {
        list($orderId, $userId) = $this->createOrder();
        CSaleOrder::PayOrder($orderId, "Y", false, true);

        $dRes = \Bitrix\Main\Mail\Internal\EventTable::getList(array(
            'filter' => array(
                'DATE_INSERT' => (new \Bitrix\Main\Type\DateTime())->add("-1M")
            )
        ));
        $hadMessage = false;
        while ($aEvent = $dRes->fetch()) {
            // произвести анализ на нахождение
            if ($hadMessage) {
                break;
            }
        }
        if (!$hadMessage) {
            $this->getAssert()->fail("Не найдено сообщение пользователю об оплате заказа");
        }
    }

    /**
     * @return array
     */
    private function createOrder() {
        $userId = (new \Domain\TestArtifacts\UserMaker("test@user.com"))
            ->get();

        $dbResult = CCatalog::GetList(array(), array("ACTIVE" => "Y"));
        $productsIds = array();
        $count = self::COUNT_PRODUCTS;
        while (($arProduct = $dbResult->Fetch()) && $count--) {
            $productsIds[] = $arProduct['ID'];
        }

        $orderId = (new \Domain\TestArtifacts\OrderMaker($userId))
            ->useProducts($productsIds)
            ->create();
        return array($orderId, $userId);
    }
}
