# Gas Booking Portal - Diagram Scenarios

This document outlines various scenarios for creating different types of diagrams for the Gas Booking Portal project. These scenarios will help visualize the system's architecture, data flow, user interactions, and more.

## Scenarios

### 1. Use Case Diagram

*   **Scenario:** A new customer wants to register on the portal. They need to provide their consumer ID, mobile number, and desired password. The system should verify the consumer ID and mobile number before allowing registration.
*   **Actors:**
    *   **Customer:** Represents a person who wants to use the gas booking portal.
    *   **System:** Represents the gas booking portal itself.
*   **Use Cases:**
    *   **Register as Consumer:** The customer provides their details to register on the portal.
        *   **Description:** This use case describes the process of a new customer registering on the portal.
        *   **Steps:**
            1.  Customer enters Consumer ID, Mobile Number, and Password.
            2.  System validates the entered data.
            3.  System creates a new consumer account.
    *   **Verify Consumer ID and Mobile Number:** The system checks the validity of the provided consumer ID and mobile number.
        *   **Description:** This use case details the verification process.
        *   **Steps:**
            1.  System receives Consumer ID and Mobile Number.
            2.  System checks the database for a matching record.
            3.  System returns verification status.

### 2. Activity Diagram

*   **Scenario:** A registered consumer logs into the system. The system needs to authenticate the user based on their Consumer ID and password. Upon successful login, the consumer is redirected to their dashboard.
*   **Activity:**
    *   **Start:** The process begins.
    *   **Input Consumer ID and Password:** The consumer enters their login credentials.
    *   **System Authenticates User:** The system verifies the credentials against the stored data.
    *   **Decision:**
        *   **If Authentication Successful:** The process continues to the "Redirect to Consumer Dashboard" activity.
        *   **If Authentication Fails:** The process moves to the "Display Error Message" activity.
    *   **Redirect to Consumer Dashboard:** The consumer is taken to their personalized dashboard.
    *   **Display Error Message:** An error message is shown to the consumer, prompting them to re-enter their credentials.
    *   **End:** The process concludes.

### 3. Sequence Diagram

*   **Scenario:** A consumer books a new gas cylinder order. The consumer confirms the order, and the system sends a notification to the distributor. The distributor then updates the order status.
*   **Objects:**
    *   **Consumer:** The registered user placing the order.
    *   **System:** The gas booking portal application.
    *   **Distributor:** The gas distributor responsible for fulfilling the order.
    *   **Database:** The system's database for storing order and user information.
*   **Sequence:**
    1.  **Consumer -> System:** Book Order (Order Details)
    2.  **System -> System:** Verify Order Details
    3.  **System -> Distributor:** Send Notification (Order Details)
    4.  **Distributor -> Distributor:** Receive Notification
    5.  **Distributor -> System:** Update Order Status (Order ID, Status)
    6.  **System -> Database:** Update Order Status (Order ID, Status)

### 4. Class Diagram

*   **Scenario:** Identify the main entities in the system and their relationships.
*   **Classes:**
    *   **User:**
        *   Attributes: UserID, Username, Password, Role
        *   Methods: Login(), Logout()
    *   **Consumer:**
        *   Attributes: ConsumerID, Name, Address, MobileNo, Email, Password, DistributorID
        *   Methods: BookOrder(), TrackOrder(), ViewOrderHistory(), SubmitFeedback()
    *   **Distributor:**
        *   Attributes: DistributorID, Name, Address, City, MobileNo, Email, Password
        *   Methods: AddConsumer(), ManageConsumers(), CheckOrders(), UpdateOrderStatus()
    *   **Admin:**
        *   Attributes: AdminID, Username, Password
        *   Methods: ManageDistributors(), ViewFeedback()
    *   **Order:**
        *   Attributes: OrderID, ConsumerID, DistributorID, Date, Time, Amount, Status
        *   Methods: PlaceOrder(), UpdateStatus()
    *   **Feedback:**
        *   Attributes: FeedbackID, ConsumerID, DistributorID, Date, Time, Type, Subject, Description
        *   Methods: SubmitFeedback()
*   **Relationships:**
    *   User 1:1 &lt;-&gt; Consumer 1:\* (One User can be one Consumer, and one Consumer has one User account)
    *   User 1:1 &lt;-&gt; Distributor 1:\* (One User can be one Distributor, and one Distributor has one User account)
    *   User 1:1 &lt;-&gt; Admin 1:\* (One User can be one Admin, and one Admin has one User account)
    *   Order \*:1 &lt;-&gt; Consumer 1 (Many Orders belong to one Consumer)
    *   Order \*:1 &lt;-&gt; Distributor 1 (Many Orders are handled by one Distributor)
    *   Feedback \*:1 &lt;-&gt; Consumer 1 (Many Feedbacks are given by one Consumer)
    *   Feedback \*:1 &lt;-&gt; Distributor 1 (Many Feedbacks are related to one Distributor)

### 5. Component Diagram

*   **Scenario:** Show the different components of the system and their dependencies.
*   **Components:**
    *   **Web Interface:** Provides the user interface for consumers, distributors, and admins.
    *   **Authentication Module:** Handles user authentication and authorization.
    *   **Order Management Module:** Manages order placement, tracking, and updates.
    *   **Notification Module:** Sends notifications to consumers and distributors.
    *   **Database:** Stores all system data.
*   **Dependencies:**
    *   Web Interface -> Authentication Module (Uses authentication services)
    *   Web Interface -> Order Management Module (Uses order management functions)
    *   Order Management Module -> Database (Accesses and updates order data)
    *   Notification Module -> Database (Retrieves user contact information)

### 6. Deployment Diagram

*   **Scenario:** Illustrate how the system is deployed across different environments.
*   **Nodes:**
    *   **Web Server:** Hosts the web application.
        *   Software: Apache, Nginx, Node.js
    *   **Database Server:** Stores the system's data.
        *   Software: MySQL, PostgreSQL, SQLite
    *   **Email Server:** Handles sending email notifications.
        *   Software: Sendmail, Postfix, Exim
*   **Artifacts:**
    *   Web Application: The PHP, HTML, CSS, and JavaScript files.
    *   Database: The database schema and data.
    *   SMTP Server: The configuration for sending emails.

### 7. Data Flow Diagram (DFD)

*   **Scenario:** Track the flow of data when a consumer submits feedback.
*   **Processes:**
    *   **Collect Feedback:** Gathers feedback from the consumer through the web interface.
    *   **Validate Feedback:** Checks the feedback for completeness and validity.
    *   **Store Feedback:** Saves the feedback in the database.
    *   **Notify Admin:** Alerts the admin about the new feedback.
*   **Data Stores:**
    *   **Feedback Data:** Stores the feedback information in the database.
    *   **Admin Notifications:** Stores notifications for the admin.
*   **Data Flows:**
    *   Consumer -> Collect Feedback: Feedback Details
    *   Collect Feedback -> Validate Feedback: Raw Feedback
    *   Validate Feedback -> Store Feedback: Validated Feedback
    *   Store Feedback -> Feedback Data: Stored Feedback
    *   Store Feedback -> Notify Admin: New Feedback Alert
    *   Notify Admin -> Admin Notifications: Admin Alert

### 8. Entity-Relationship Diagram (ERD)

*   **Scenario:** Model the database schema for the gas booking portal.
*   **Entities:**
    *   **Consumer:**
        *   Attributes: ConsumerID (PK), Name, Address, MobileNo, Email, Password, RegistrationDate, DistributorID (FK)
    *   **Distributor:**
        *   Attributes: DistributorID (PK), Name, Address, City, Pin, MobileNo, Email, Password, Proof
    *   **Admin:**
        *   Attributes: AdminID (PK), Username, Password
    *   **Order:**
        *   Attributes: OrderID (PK), ConsumerID (FK), DistributorID (FK), Date, Time, Amount, Status
    *   **Feedback:**
        *   Attributes: FeedbackID (PK), ConsumerID (FK), DistributorID (FK), Date, Time, Type, Subject, Description
*   **Relationships:**
    *   Consumer 1:\* &lt;-&gt; Order \*:1 (One consumer can have many orders, and each order belongs to one consumer)
    *   Distributor 1:\* &lt;-&gt; Order \*:1 (One distributor can handle many orders, and each order is handled by one distributor)
    *   Consumer 1:\* &lt;-&gt; Feedback \*:1 (One consumer can submit many feedbacks, and each feedback is submitted by one consumer)
    *   Distributor 1:\* &lt;-&gt; Feedback \*:1 (One distributor can receive many feedbacks, and each feedback is related to one distributor)
    *   Consumer \*:1 &lt;-&gt; Distributor 1 (Many consumers are linked to one distributor)

## Instructions

1.  **Choose a Diagram Type:** Select the diagram type that best represents the aspect of the system you want to visualize.
2.  **Identify Key Elements:** Based on the scenario, identify the key actors, components, entities, processes, and data stores.
3.  **Define Relationships:** Determine how these elements interact with each other.
4.  **Use a Diagramming Tool:** Use a suitable tool (e.g., draw.io, Lucidchart, Visual Paradigm) to create the diagram.
5.  **Follow Notations:** Adhere to the standard notations for each diagram type (e.g., UML for use case, class, and sequence diagrams).
6.  **Review and Refine:** Ensure the diagram accurately reflects the system and is clear and understandable.
