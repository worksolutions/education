<?php

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class PaymentHandlerTestCase extends \WS\BUnit\Cases\BaseCase {

    /**
     * @test
     */
    public function orderPaymentSuccess() {
        //Обработчик платежной системы корректно устанавливает заказ в статус “оплачено”.
        //Создать заказ для оплаты определенной платежной системой;
        //Сформировать запрос уведомление об оплате заказа
        //Проверить что заказ установлен в статус “оплачен”
    }

    /**
     * @test
     */
    public function orderPaymentFail() {

    }
}