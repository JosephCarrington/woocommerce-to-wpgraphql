# woocommerce-to-wpgraphql
A WordPress plugin to expose WooCommerce products via the WP GraphQL plugin

```
query GET_PRODUCTS {
  products(last: 20) {
    edges {
      node {
        description,
        content,
        price,
        thumbnail,
        is_on_sale,
        sale_price,
        categories {
          name
        }
      }
    }
  }
}
```
