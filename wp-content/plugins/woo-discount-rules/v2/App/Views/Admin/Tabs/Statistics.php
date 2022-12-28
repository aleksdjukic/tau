<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$is_pro = Wdr\App\Helpers\Helper::hasPro();
?>
<style>
    .chart-options select {
        vertical-align: inherit;
    }

    .chart-options .chart-period-start,
    .chart-options .chart-period-end {
        padding: 4px 8px;
    }

    .chart-tooltip {
        position: absolute;
    }

    .chart-placeholder {
        margin-right: 50px;
        height: 400px;
    }

    .chart-placeholder.loading:after {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, .6);
        content: '';
    }
    #chart-container{
        padding: 20px;
        background: #fff;
    }
    #info-container{
        display: flex;
        margin-bottom: 10px;
        gap: 10px;
    }
    #info-container .wdr-card {
        width: 100%;
        padding: 0.5rem 1.5rem;
        min-width: 255px;
        box-shadow: 0 1px 1px rgb(0 0 0 / 4%);
        background: #fff;
        box-sizing: border-box;
    }
    #info-container .total-orders {
        border-left: 3px solid #0092e1;
    }
    #info-container .total-revenue {
        border-left: 3px solid #45cc7a;
    }
    #info-container .discounted-amount {
        border-left: 3px solid #e59b42;
    }
    #info-container .total-free-shipping {
        border-left: 3px solid #4f31d5;
    }
    #info-container .wdr-card h4 {
        margin: 4px 0;
    }
</style>
<br>
<div id="wpbody-content" class="awdr-container">
    <form method="post" name="wdr-statistics" class="chart-options">
        <div class="wdr-rule-statistics">
            <div class="statistics_date_range">
                <select name="period" class="chart-period" style="height: 33px">
                    <option value="this_week"><?php _e('This Week', 'woo-discount-rules'); ?></option>
                    <option value="this_month"><?php _e('This Month', 'woo-discount-rules'); ?></option>
                    <option value="custom"><?php _e('Custom Range', 'woo-discount-rules'); ?></option>
                </select>
            </div>
            <div class="wdr-dateandtime-value">
                <input type="text"
                       name="from"
                       class="wdr-condition-date wdr-title chart-period-start" data-class="start_dateonly"
                       placeholder="<?php esc_attr_e('From: yyyy/mm/dd', 'woo-discount-rules'); ?>" data-field="date"
                       autocomplete="off"
                       id="rule_datetime_from" value="<?php if (isset($date[0]) && !empty($date[0])) {
                    echo esc_attr($date[0]);
                } ?>" style="height: 34px;">
            </div>
            <div class="wdr-dateandtime-value">
                <input type="text"
                       name="to"
                       class="wdr-condition-date wdr-title chart-period-end" data-class="end_dateonly"
                       placeholder="<?php _e('To: yyyy/mm/dd', 'woo-discount-rules'); ?>"
                       data-field="date" autocomplete="off"
                       id="rule_datetime_to" value="<?php if (isset($date[1]) && !empty($date[1])) {
                    echo esc_attr($date[1]);
                } ?>" style="height: 34px;">
            </div>
            <div class="awdr-report-type" >
                <select name="type" class="chart-type awdr-show-report-limit" style="height: 33px">
                    <?php foreach ( $charts as $group => $charts_by_group ): ?>
                        <optgroup label="<?php echo esc_attr($group); ?>">
                            <?php foreach ( $charts_by_group as $key => $name ): ?>
                                <option value="<?php echo esc_attr($key) ?>"><?php echo esc_html($name) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <input type="hidden" name="awdr_nonce" value="<?php echo esc_attr(\Wdr\App\Helpers\Helper::create_nonce('wdr_ajax_report')); ?>">
                <button type="submit" class="update-chart btn btn-success"><?php _e('Update Chart', 'woo-discount-rules'); ?></button>
            </div>
        </div>
    </form>
    <br/>
    <div id="info-container" style="display: none;">
        <div class="wdr-card total-orders">
            <h4><?php esc_html_e("Discounted orders", 'woo-discount-rules'); ?></h4>
            <h4 id="total-orders">-</h4>
        </div>
        <div class="wdr-card total-revenue">
            <h4><?php esc_html_e("Total sales", 'woo-discount-rules'); ?></h4>
            <h4 id="total-revenue">-</h4>
        </div>
        <div class="wdr-card discounted-amount">
            <h4><?php esc_html_e("Discounted amount", 'woo-discount-rules'); ?></h4>
            <h4 id="discounted-amount">-</h4>
        </div>
        <?php if ($is_pro) { ?>
            <div class="wdr-card total-free-shipping">
                <h4><?php esc_html_e("Orders with free shipping", 'woo-discount-rules'); ?></h4>
                <h4 id="total-free-shipping">-</h4>
            </div>
        <?php } ?>
    </div>
    <div id="chart-container"></div>
    <div class="clear"></div>
</div>