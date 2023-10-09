import pandas as pd

amount = 0.05  # 비트코인 양 (예: 0.001 BTC)

range_start = 20000  # 구간 시작 가격
range_end = 45000  # 구간 끝 가격
interval = 1000  # 구간 가격 간격

buy_prices = list(range(range_start, range_end, interval))
sell_prices = list(range(range_start + interval, range_end + interval, interval))

filled_orders = set()  # 구간별로 주문이 체결된 구간 추적

historical_data = pd.read_csv('historical_data.csv', index_col='timestamp')

balance = 100000.0  # 초기 잔액 100,000 USDT
BTC_balance = 0.0  # 초기 비트코인 잔액

def execute_trade(historical_data):
    global balance, BTC_balance, buy_prices, sell_prices, filled_orders

    for index, row in historical_data.iterrows():
        current_price = row['close']

        for buy_price, sell_price in zip(buy_prices, sell_prices):
            if buy_price not in filled_orders and buy_price <= current_price < sell_price:
                # 매수
                order_cost = buy_price * amount
                if balance >= order_cost:
                    balance -= order_cost
                    BTC_balance += amount
                    print(f"{index} 매수 완료: {buy_price} -> {sell_price}")
                    filled_orders.add(buy_price)

            if sell_price <= current_price and buy_price in filled_orders:
                # 매도
                order_value = sell_price * amount
                balance += order_value
                BTC_balance -= amount
                print(f"{index} 매도 완료: {buy_price} -> {sell_price}")
                filled_orders.remove(buy_price)
                
execute_trade(historical_data)

print("최종 USDT 잔액:", round(balance, 2))
print("최종 BTC 잔액:", round(BTC_balance, 8))