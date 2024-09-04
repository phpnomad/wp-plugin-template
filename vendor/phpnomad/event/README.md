# PHPNomad Repository

This is a new repository for PHPNomad. I'm sure it'll be really cool, but I don't have much else to say about it yet. Hang tight, I'm sure this will change :)

## Example of Binding For an Integration

```php
class OrderReceived implements Event
{
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }
    public static function getId(): string
    {
        return 'orderReceived';
    }
}

final class WooCommerceIntegration implements Loadable
{
    use HasSettableContainer;

    public function load(): void
    {
        $bindingInstance = $this->container->get(ActionBindingStrategy::class);

        $bindingInstance->bindAction(
            OrderReceived::class,
            'woocommerce_order_status_completed',
            fn(int $orderId) => new OrderReceived($orderId)
        );
    }
}
```