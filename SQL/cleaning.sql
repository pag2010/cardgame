BEGIN
	declare id_clean int;
	declare price_clean int;
	declare seller_clean varchar(50);
	declare buyer_clean varchar(50);
	declare buyer_current varchar(50);
	declare price_current int;
	declare money_buyer int;
	declare money_seller int;
	DECLARE cur1 CURSOR FOR SELECT id, seller FROM auction_cards where sell_date<=NOW();
	DECLARE cur2 CURSOR FOR SELECT member, price FROM auction_queue where auction_id=id_clean;
	OPEN cur1;
	OPEN cur2;
	set autocommit=0;
	 read_loop: LOOP
    FETCH cur1 INTO id_clean, seller_clean;
    IF done THEN
      LEAVE read_loop;
    END IF;
    		clean_loop: LOOP
    		FETCH cur2 INTO buyer_current, price_current;
    		
    		IF done THEN
    				
      		LEAVE read_loop;
    		END IF;
    		
    		select money from user_auth where login=buyer_current into money_buyer;
    		if money_buyer>=price_current then
    			update user_auth set money=money_buyer-price_current where login=buyer_current;
    			select money from user_auth where login=seller_clean into money_seller;
    			update user_auth set money=money_seller+price_current where login=seller_clean;
    			delete from auction_queue where auction_id=id_clean;
    		end if;
  			END LOOP;
  END LOOP;

  CLOSE cur1;
END