import ccxt
import time
from datetime import datetime

# 파일로부터 apiKey, Secret 읽기 
with open("api.txt") as f:
    lines = f.readlines()
    api_key = lines[0].strip() 
    secret = lines[1].strip() 

# binance 객체 생성
exchange = ccxt.binance(config={
    'apiKey': api_key,
    'secret': secret
})

symbol = 'BTC/USDT'
amount = 0.0005  # 비트코인 양 (예: 0.001 BTC)

range_start = 20000  # 구간 시작 가격
range_end = 45000  # 구간 끝 가격
interval = 1000  # 구간 가격 간격

buy_prices = list(range(range_start, range_end, interval))
sell_prices = list(range(range_start + interval, range_end + interval, interval))

filled_orders = set()  # 구간별로 주문이 체결된 구간 추적

while True:
    try:
        ticker = exchange.fetch_ticker(symbol)
        current_price = ticker['last']
        now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        print(f'{now} 현재 가격: {current_price:.2f}')

        for buy_price, sell_price in zip(buy_prices, sell_prices):
            if buy_price not in filled_orders and buy_price <= current_price < sell_price:
                # 매수
                order = exchange.create_market_buy_order(symbol, amount)
                now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                print(f"{now} 매수 완료: {buy_price} -> {sell_price}")
                filled_orders.add(buy_price)

            if sell_price <= current_price and buy_price in filled_orders:
                # 매도
                order = exchange.create_market_sell_order(symbol, amount)
                now = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
                print(f"{now} 매도 완료: {buy_price} -> {sell_price}")
                filled_orders.remove(buy_price)

    except Exception as e:
        print("매매 중 에러 발생:", e)

    time.sleep(1)  # 1초마다 가격 확인