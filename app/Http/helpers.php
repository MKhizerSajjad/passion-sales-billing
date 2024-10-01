<?php
    function getGeneralStatus($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Active', '<span class="badge bg-primary-light">Active</span>'],
            '2' => ['Inactive', '<span class="badge bg-warning-light">Inactive</span>']
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

    function getBoolStatus($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Yes', '<span class="badge bg-primary-light">Yes</span>'],
            '2' => ['No', '<span class="badge bg-warning-light">No</span>']
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

    function getUserType($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Admin', '<span class="badge bg-primary-light">Admin</span>'],
            '2' => ['Vendor', '<span class="badge bg-warning-light">Vendor</span>'],
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

    function getRelations($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Yes', '<span class="badge bg-primary-light">Yes</span>'],
            '2' => ['No', '<span class="badge bg-warning-light">No</span>']
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

    function getLeadStatus($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Made', '<span class="badge bg-info-light">Made</span>'],
            '2' => ['Made & Assigned', '<span class="badge bg-secondary-light">Made & Assigned</span>'],
            '3' => ['Negotiating', '<span class="badge bg-info-light">Negotiating</span>'],
            '4' => ['Waiting for customer response', '<span class="badge bg-warning-light">Waiting for customer response</span>'],
            '5' => ['Suspended', '<span class="badge bg-danger-light">Suspended</span>'],
            '6' => ['Withdrawed', '<span class="badge bg-primary-light">Withdrawed</span>'],
            '7' => ['Resolved', '<span class="badge bg-success-light">Resolved</span>'],
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

    function getLeadType($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Meta', '<span class="badge bg-info-light">Meta</span>'],
            '2' => ['Linkedin', '<span class="badge bg-secondary-light">Linkedin</span>'],
            '3' => ['Direct', '<span class="badge bg-info-light">Direct</span>'],
            '4' => ['Walkin', '<span class="badge bg-warning-light">Walkin</span>'],
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

    function getPaymentStatus($status = null, $type = null)
    {
        $statuses = [
            '1' => ['Paid', '<span class="badge bg-success-light">Paid</span>'],
            '2' => ['Late Payment', '<span class="badge bg-primary-light">Late Payment</span>'],
            '3' => ['Partially Paid', '<span class="badge bg-info-light">Partially Paid</span>'],
            '4' => ['Pending', '<span class="badge bg-warning-light">Pending</span>'],
            '5' => ['Unpaid', '<span class="badge bg-danger-light">Unpaid</span>'],
        ];

        return
            isset($statuses[$status])
            ? ($type === 'badge' ? $statuses[$status][1] : $statuses[$status][0])
            : ($type === 'badge' ? array_column($statuses, 1) : array_column($statuses, 0));
    }

?>
