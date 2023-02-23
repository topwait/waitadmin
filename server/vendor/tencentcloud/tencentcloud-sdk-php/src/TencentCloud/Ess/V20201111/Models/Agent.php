<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Ess\V20201111\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 主企业代子企业操作 或 渠道子客应用相关信息
 *
 * @method string getAppId() 获取应用编号,32位字符串
 * @method void setAppId(string $AppId) 设置应用编号,32位字符串
 * @method string getProxyAppId() 获取主组织的应用号
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProxyAppId(string $ProxyAppId) 设置主组织的应用号
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getProxyOrganizationId() 获取主组织在平台的机构编号
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProxyOrganizationId(string $ProxyOrganizationId) 设置主组织在平台的机构编号
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getProxyOperator() 获取主组织的操作人
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setProxyOperator(string $ProxyOperator) 设置主组织的操作人
注意：此字段可能返回 null，表示取不到有效值。
 */
class Agent extends AbstractModel
{
    /**
     * @var string 应用编号,32位字符串
     */
    public $AppId;

    /**
     * @var string 主组织的应用号
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProxyAppId;

    /**
     * @var string 主组织在平台的机构编号
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProxyOrganizationId;

    /**
     * @var string 主组织的操作人
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ProxyOperator;

    /**
     * @param string $AppId 应用编号,32位字符串
     * @param string $ProxyAppId 主组织的应用号
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ProxyOrganizationId 主组织在平台的机构编号
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ProxyOperator 主组织的操作人
注意：此字段可能返回 null，表示取不到有效值。
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("AppId",$param) and $param["AppId"] !== null) {
            $this->AppId = $param["AppId"];
        }

        if (array_key_exists("ProxyAppId",$param) and $param["ProxyAppId"] !== null) {
            $this->ProxyAppId = $param["ProxyAppId"];
        }

        if (array_key_exists("ProxyOrganizationId",$param) and $param["ProxyOrganizationId"] !== null) {
            $this->ProxyOrganizationId = $param["ProxyOrganizationId"];
        }

        if (array_key_exists("ProxyOperator",$param) and $param["ProxyOperator"] !== null) {
            $this->ProxyOperator = $param["ProxyOperator"];
        }
    }
}
